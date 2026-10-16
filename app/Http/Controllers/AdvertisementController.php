<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function click(Advertisement $advertisement, Request $request): RedirectResponse
    {
        $advertisement->recordClick();

        if (!empty($advertisement->target_url)) {
            return redirect()->away($advertisement->target_url);
        }

        return redirect()->route('home');
    }
}
