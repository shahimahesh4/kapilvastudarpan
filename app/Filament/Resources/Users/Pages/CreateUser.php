<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email_verified_at'] = now();
        unset($data['is_verified']);

        // Only super admin can create accounts with super_admin role
        if (! auth()->user()?->isSuperAdmin() && isset($data['role']) && $data['role'] === \App\Models\User::ROLE_SUPER_ADMIN) {
            $data['role'] = \App\Models\User::ROLE_ADMIN;
        }

        return $data;
    }
}
