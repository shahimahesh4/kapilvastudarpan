<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use ErrorException;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as BaseMiddleware;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PreventRequestsDuringMaintenance extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \ErrorException
     */
    public function handle($request, Closure $next)
    {
        // 1. If path is in except array (e.g. /stnapanel*, /up, livewire*), allow immediately
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        // 2. If maintenance mode is not active, proceed normally
        if (! $this->app->maintenanceMode()->active()) {
            return $next($request);
        }

        // 3. If user is a logged-in admin (admin or super_admin), allow full frontend access
        if ($this->isLoggedInAdmin($request)) {
            return $next($request);
        }

        // 4. Otherwise, perform standard Laravel maintenance mode checks (secret, bypass cookie, 503 template)
        try {
            $data = $this->app->maintenanceMode()->data();
        } catch (ErrorException $exception) {
            if (! $this->app->maintenanceMode()->active()) {
                return $next($request);
            }

            throw $exception;
        }

        if (isset($data['secret']) && $request->path() === $data['secret']) {
            return $this->bypassResponse($data['secret']);
        }

        if ($this->hasValidBypassCookie($request, $data)) {
            return $next($request);
        }

        if (isset($data['redirect'])) {
            $path = $data['redirect'] === '/'
                ? $data['redirect']
                : trim($data['redirect'], '/');

            if ($request->path() !== $path) {
                return redirect($path);
            }
        }

        if (isset($data['template'])) {
            return response(
                $data['template'],
                $data['status'] ?? 503,
                $this->getHeaders($data)
            );
        }

        throw new HttpException(
            $data['status'] ?? 503,
            'Service Unavailable',
            null,
            $this->getHeaders($data)
        );
    }

    /**
     * Check if the incoming request belongs to an authenticated admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isLoggedInAdmin($request): bool
    {
        try {
            // Check in-memory authenticated user (e.g. actingAs in tests or pre-resolved auth)
            if ($this->app->bound('auth')) {
                $user = $this->app['auth']->user();
                if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                    return true;
                }
            }

            // Check request user instance
            if ($request->user() && method_exists($request->user(), 'isAdmin') && $request->user()->isAdmin()) {
                return true;
            }

            // Check session ID from incoming browser cookie
            $sessionId = $this->getSessionIdFromRequest($request);
            if (! $sessionId) {
                return false;
            }

            $userId = null;

            // Direct database lookup if session driver is database
            if (config('session.driver') === 'database') {
                $sessionTable = config('session.table', 'sessions');
                $sessionRow = DB::table($sessionTable)
                    ->where('id', $sessionId)
                    ->select('user_id')
                    ->first();

                if ($sessionRow && $sessionRow->user_id) {
                    $userId = $sessionRow->user_id;
                }
            }

            // Fallback for file or other session drivers
            if (! $userId && $this->app->bound('session')) {
                $handler = $this->app['session']->driver()->getHandler();
                $payload = $handler->read($sessionId);
                if ($payload) {
                    $data = @unserialize($payload);
                    if ($data === false) {
                        $data = @unserialize(base64_decode($payload));
                    }
                    if (is_array($data)) {
                        foreach ($data as $key => $value) {
                            if (str_starts_with($key, 'login_') && is_numeric($value)) {
                                $userId = (int) $value;
                                break;
                            }
                        }
                    }
                }
            }

            if ($userId) {
                $user = User::find($userId);
                if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // Fail safe on any exception to standard maintenance mode response
            return false;
        }

        return false;
    }

    /**
     * Decrypt and extract the session ID from the request cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function getSessionIdFromRequest($request): ?string
    {
        $cookieName = config('session.cookie');
        if (! $cookieName) {
            return null;
        }

        $rawCookie = $request->cookies->get($cookieName);
        if (! $rawCookie || ! is_string($rawCookie)) {
            return null;
        }

        try {
            if ($this->app->bound('encrypter')) {
                return $this->app['encrypter']->decrypt($rawCookie, false);
            }
        } catch (\Throwable $e) {
            // If decryption fails (e.g. in test environments using raw cookies), use raw cookie
            return $rawCookie;
        }

        return $rawCookie;
    }
}
