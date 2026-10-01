<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'produk';

    protected static ?string $pluralModelLabel = 'produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori yang tersedia')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih kategori yang sudah ada')
                            ->helperText('Pilih kategori yang sudah tersedia atau buat kategori baru di bawah ini.')
                            ->required(fn (Get $get) => blank($get('custom_category'))),
                        Forms\Components\TextInput::make('custom_category')
                            ->label('Kategori baru (opsional)')
                            ->placeholder('Contoh: Sound System Premium')
                            ->helperText('Jika kategori belum ada, masukkan nama baru di sini. Sistem akan membuat kategori otomatis.')
                            ->required(fn (Get $get) => blank($get('category_id'))),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->placeholder('Hubungi Kami')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('specification')
                            ->label('Spesifikasi')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('meta_description')
                            ->label('Deskripsi SEO')
                            ->maxLength(160)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Foto Produk')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Foto utama')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->imageEditor()
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        Forms\Components\Repeater::make('images')
                            ->label('Galeri foto')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products/gallery')
                                    ->required(),
                                Forms\Components\TextInput::make('order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->orderColumn('order')
                            ->addActionLabel('Tambah foto')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Tampilkan di situs')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Produk unggulan')
                            ->default(false),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Tampil')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status publikasi'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Produk unggulan'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}