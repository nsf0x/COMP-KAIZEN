<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Klien';

    protected static ?string $modelLabel = 'klien';

    protected static ?string $pluralModelLabel = 'klien';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Klien')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama klien')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disk('public')
                        ->directory('clients')
                        ->imageEditor(),
                    Forms\Components\TextInput::make('website')
                        ->label('Situs web')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Publikasi')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Tampilkan di situs')
                        ->default(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')->label('Logo')->disk('public'),
                Tables\Columns\TextColumn::make('name')->label('Nama klien')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('website')->label('Situs web')->url(fn (Client $record) => $record->website, true),
                Tables\Columns\IconColumn::make('is_published')->label('Tayang')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Status publikasi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
