<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveResource\Pages;
use App\Models\Live;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class LiveResource extends Resource
{
    protected static ?string $model = Live::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Lives';
    protected static ?string $pluralModelLabel = 'Lives';
    protected static ?string $modelLabel = 'Live';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            DateTimePicker::make('start_time')
                ->label('Date et heure du live')
                ->required(),

            Select::make('platform')
                ->label('Plateforme')
                ->options([
                    'youtube' => 'YouTube',
                    'tiktok' => 'TikTok',
                    'site' => 'Sur le Site',
                ])
                ->required(),

            TextInput::make('url')
                ->label('URL du live')
                ->helperText('Lien vers YouTube ou TikTok'),

            Toggle::make('is_active')
                ->label('Activer ce live'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                TextColumn::make('platform')->label('Plateforme')->sortable(),
                TextColumn::make('start_time')->label('Date du live')->dateTime()->sortable(),
                IconColumn::make('is_active')->boolean()->label('Actif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLives::route('/'),
            'create' => Pages\CreateLive::route('/create'),
            'edit' => Pages\EditLive::route('/{record}/edit'),
        ];
    }
}
