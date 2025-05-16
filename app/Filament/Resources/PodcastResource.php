<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PodcastResource\Pages;
use App\Models\Podcast;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;

class PodcastResource extends Resource
{
    protected static ?string $model = Podcast::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Podcasts';
    protected static ?string $pluralLabel = 'Podcasts';
    protected static ?string $modelLabel = 'Podcast';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required(),
            Textarea::make('description')->rows(4)->nullable(),
            
            FileUpload::make('audio_file')
                ->label('Fichier Audio')
                ->directory('podcasts')
                ->disk('public')
                ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/mp4'])
                ->required(),

            FileUpload::make('cover_image')
                ->label('Image de couverture')
                ->image()
                ->imagePreviewHeight('150')
                ->directory('podcasts/covers')
                ->disk('public')
                ->nullable(),

            DatePicker::make('release_date')
                ->label('Date de publication')
                ->nullable(),

            Toggle::make('is_active')
                ->label('Actif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Image')
                    ->disk('public')
                    ->height(60)
                    ->width(60)
                    ->circular(),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('release_date')
                    ->label('Date')
                    ->date(),

                BooleanColumn::make('is_active')
                    ->label('Actif'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Ajouté le'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPodcasts::route('/'),
            'create' => Pages\CreatePodcast::route('/create'),
            'edit' => Pages\EditPodcast::route('/{record}/edit'),
        ];
    }
}
