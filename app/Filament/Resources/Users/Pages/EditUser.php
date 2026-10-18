<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        /** @var User $record */
        $record = $this->getRecord();
        $user = auth()->user();

        if ($record->isSuperAdmin() && ! $user?->isSuperAdmin()) {
            abort(403, 'Administrators cannot edit Super Admin accounts.');
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['is_verified']);

        // Don't overwrite password if left blank
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Only Super Admin can assign the super_admin role
        if (! auth()->user()?->isSuperAdmin() && isset($data['role']) && $data['role'] === User::ROLE_SUPER_ADMIN) {
            unset($data['role']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function (): bool {
                    /** @var User $record */
                    $record = $this->getRecord();
                    $user = auth()->user();
                    if (! $user || ! $user->isAdmin()) {
                        return false;
                    }
                    if ($record->id === $user->id) {
                        return false;
                    }
                    if ($record->isSuperAdmin() && ! $user->isSuperAdmin()) {
                        return false;
                    }
                    return true;
                })
                ->modalHeading(fn (): string => $this->getRecord()->articles()->exists()
                    ? 'Reassign Articles & Delete User (लेखहरू हस्तान्तरण तथा प्रयोगकर्ता मेटाउने)'
                    : 'Delete User')
                ->modalDescription(fn (): ?string => $this->getRecord()->articles()->exists()
                    ? "यो प्रयोगकर्ताले {$this->getRecord()->articles()->count()} वटा लेखहरू लेख्नुभएको छ। प्रयोगकर्ता मेटाउनुअघि ती सम्पूर्ण लेखहरू सुरक्षित राख्न अर्को एडमिन वा सम्पादकलाई हस्तान्तरण गर्नुहोस्।"
                    : 'के तपाईं यो प्रयोगकर्ता मेटाउन निश्चित हुनुहुन्छ?')
                ->form(function (): array {
                    /** @var User $record */
                    $record = $this->getRecord();
                    if (! $record->articles()->exists()) {
                        return [];
                    }

                    return [
                        Select::make('reassign_to')
                            ->label('Transfer all articles to (लेखहरू नयाँ लेखकलाई हस्तान्तरण गर्नुहोस्)')
                            ->helperText('छनोट गरिएको एडमिन वा सम्पादक यी सम्पूर्ण लेखहरूको नयाँ लेखक/मालिक बन्नेछन्।')
                            ->options(function () use ($record) {
                                return User::query()
                                    ->where('id', '!=', $record->id)
                                    ->whereIn('role', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_EDITOR])
                                    ->pluck('name', 'id');
                            })
                            ->default(function () use ($record) {
                                $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->where('id', '!=', $record->id)->first();
                                return $superAdmin?->id;
                            })
                            ->required()
                            ->native(false),
                    ];
                })
                ->using(function (array $data): bool {
                    /** @var User $record */
                    $record = $this->getRecord();
                    if (! empty($data['reassign_to'])) {
                        $record->reassignArticlesTo($data['reassign_to']);
                    }
                    return (bool) $record->delete();
                }),
        ];
    }
}
