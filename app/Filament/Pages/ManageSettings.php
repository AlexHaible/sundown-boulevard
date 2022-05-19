<?php

namespace App\Filament\Pages;

use Filament\Pages\SettingsPage;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ManageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = GeneralSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                TextInput::make('restaurant_name')
                    ->label('Restaurant Name')
                    ->required(),
                TextInput::make('tables')
                    ->label('Number of tables')
                    ->numeric()
                    ->mask(
                        fn (TextInput\Mask $mask) => $mask
                            ->numeric()
                            ->integer()
                    )->required(),
                Select::make('opens_at')
                    ->label('Opens at')
                    ->required()
                    ->options($this->generateHours())
                    ->default('16:00'),
                Select::make('closes_at')
                    ->label('Closes at')
                    ->required()
                    ->options($this->generateHours())
                    ->default('22:00'),
                Select::make('intervals')
                    ->label('Intervals')
                    ->required()
                    ->options([
                        'PT1H' => 'Hourly',
                        'PT30M' => 'Half-Hourly',
                        'PT15M' => 'Quarterly',
                    ]),
            ])->columns(2)
        ];
    }

    protected function generateHours(): array
    {
        $h = 0;
        $formatter = [];
        while ($h < 24) {
            $format = date('H:i', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
            $formatter[$format] = $format;
            $h++;
        }
        return $formatter;
    }
}
