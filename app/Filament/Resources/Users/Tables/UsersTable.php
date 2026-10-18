<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn (User $record): string => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?: 'User') . '&background=f1f5f9&color=64748b'),

                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): ?string => $record->phone),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied to clipboard')
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->icon(fn (User $record): ?string => $record->isSuperAdmin() ? 'heroicon-m-shield-check' : null)
                    ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        User::ROLE_SUPER_ADMIN => 'danger',
                        User::ROLE_ADMIN => 'warning',
                        User::ROLE_EDITOR => 'info',
                        User::ROLE_PUBLISHER => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('articles_count')
                    ->label('Articles')
                    ->counts('articles')
                    ->badge()
                    ->color('secondary')
                    ->sortable(),

                TextColumn::make('email_verified_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Verified' : 'Unverified')
                    ->color(fn ($state): string => $state ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter by Role')
                    ->options(User::ROLES),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->visible(function (User $record): bool {
                            $user = auth()->user();
                            if (! $user || ! $user->isAdmin()) {
                                return false;
                            }
                            if ($record->isSuperAdmin() && ! $user->isSuperAdmin()) {
                                return false;
                            }
                            return true;
                        }),

                    DeleteAction::make()
                        ->visible(function (User $record): bool {
                            $user = auth()->user();
                            if (! $user || ! $user->isAdmin()) {
                                return false;
                            }
                            // Cannot delete own account
                            if ($record->id === $user->id) {
                                return false;
                            }
                            // Admin cannot delete super admin
                            if ($record->isSuperAdmin() && ! $user->isSuperAdmin()) {
                                return false;
                            }
                            return true;
                        })
                        ->modalHeading(fn (User $record): string => $record->articles()->exists()
                            ? 'Reassign Articles & Delete User (लेखहरू हस्तान्तरण तथा प्रयोगकर्ता मेटाउने)'
                            : 'Delete User')
                        ->modalDescription(fn (User $record): ?string => $record->articles()->exists()
                            ? "यो प्रयोगकर्ताले {$record->articles()->count()} वटा लेखहरू लेख्नुभएको छ। प्रयोगकर्ता मेटाउनुअघि ती सम्पूर्ण लेखहरू सुरक्षित राख्न अर्को एडमिन वा सम्पादकलाई हस्तान्तरण गर्नुहोस्।"
                            : 'के तपाईं यो प्रयोगकर्ता मेटाउन निश्चित हुनुहुन्छ?')
                        ->form(function (User $record): array {
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
                        ->using(function (User $record, array $data): bool {
                            if (! empty($data['reassign_to'])) {
                                $record->reassignArticlesTo($data['reassign_to']);
                            }
                            return (bool) $record->delete();
                        }),
                ])
                ->visible(function (User $record): bool {
                    $user = auth()->user();
                    if (! $user || ! $user->isAdmin()) {
                        return false;
                    }
                    if ($record->isSuperAdmin() && ! $user->isSuperAdmin()) {
                        return false;
                    }
                    return true;
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $user = auth()->user();
                            $records->each(function (User $record) use ($user) {
                                if ($user && $record->id !== $user->id && ! $record->isSuperAdmin()) {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }
}
