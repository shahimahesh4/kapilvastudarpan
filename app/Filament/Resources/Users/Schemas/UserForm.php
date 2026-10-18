<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('User Account Details')
                    ->description('Manage user credentials, role permissions, and profile information.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g. Ram Sharma'),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->placeholder('user@kapilvastudarpan.com'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('role')
                                ->label('Role / Permission Level')
                                ->options(function () {
                                    $currentUser = auth()->user();
                                    $roles = User::ROLES;
                                    // If current user is not Super Admin, they cannot create/assign Super Admin
                                    if ($currentUser && ! $currentUser->isSuperAdmin()) {
                                        unset($roles[User::ROLE_SUPER_ADMIN]);
                                    }
                                    return $roles;
                                })
                                ->required()
                                ->default(User::ROLE_SUBSCRIBER)
                                ->native(false)
                                ->helperText('Determines panel access and administrative privileges.'),

                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(50)
                                ->placeholder('+977 98XXXXXXXX'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->revealable()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(fn ($state) => filled($state))
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->helperText(fn (string $operation): string => $operation === 'edit'
                                    ? 'Leave blank to keep existing password'
                                    : 'Minimum 8 characters recommended'),

                            Toggle::make('is_verified')
                                ->label('Email Verified')
                                ->helperText('Mark user email as verified immediately')
                                ->default(true)
                                ->dehydrated(false)
                                ->afterStateHydrated(function ($component, ?User $record) {
                                    $component->state($record ? $record->email_verified_at !== null : true);
                                }),
                        ]),

                        Textarea::make('bio')
                            ->label('Biography / Notes')
                            ->rows(3)
                            ->placeholder('Short description or role responsibilities...')
                            ->columnSpanFull(),

                        FileUpload::make('avatar')
                            ->label('Avatar / Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
