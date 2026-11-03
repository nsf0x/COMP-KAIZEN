<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Testimoni';

    protected static ?string $modelLabel = 'testimoni';

    protected static ?string $pluralModelLabel = 'testimoni';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Pelanggan')
                ->schema([
                    Forms\Components\TextInput::make('client_name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('event_type')
                        ->label('Jenis event')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('rented_product')
                        ->label('Produk yang disewa')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('location')
                        ->label('Lokasi')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('event_date')
                        ->label('Tanggal event'),
                    Forms\Components\Textarea::make('message')
                        ->label('Testimoni')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('rating')
                        ->label('Rating')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(5)
                        ->default(5)
                        ->required(),
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
                Tables\Columns\TextColumn::make('client_name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('event_type')->label('Jenis event')->searchable(),
                Tables\Columns\TextColumn::make('rented_product')->label('Produk disewa')->searchable(),
                Tables\Columns\TextColumn::make('location')->label('Lokasi')->searchable(),
                Tables\Columns\TextColumn::make('event_date')->label('Tanggal')->date('d M Y')->sortable(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
