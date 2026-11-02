<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaPageResource\Pages;
use App\Models\AreaPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AreaPageResource extends Resource
{
    protected static ?string $model = AreaPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Halaman Area';

    protected static ?string $modelLabel = 'halaman area';

    protected static ?string $pluralModelLabel = 'halaman area';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Konten Area')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul halaman')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->label('Area')
                        ->disabled()
                        ->dehydrated(),
                    Forms\Components\Textarea::make('unique_content')
                        ->label('Konten unik terverifikasi')
                        ->helperText('Tulis informasi khusus area ini. Jangan memakai paragraf generik yang sama untuk semua kota.')
                        ->rows(6)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('delivery_time')
                        ->label('Waktu/jadwal pengiriman terverifikasi')
                        ->rows(3),
                    Forms\Components\Textarea::make('venue_details')
                        ->label('Informasi venue terverifikasi')
                        ->rows(3),
                    Forms\Components\Textarea::make('portfolio_details')
                        ->label('Portofolio area terverifikasi')
                        ->rows(3),
                ])
                ->columns(2),
            Forms\Components\Section::make('Publikasi')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Publikasikan halaman')
                        ->default(false)
                        ->helperText('Halaman tetap 404 dan tidak masuk sitemap sampai konten unik serta salah satu detail area terisi.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Halaman')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Area')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publikasi')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Status publikasi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreaPages::route('/'),
            'edit' => Pages\EditAreaPage::route('/{record}/edit'),
        ];
    }
}
