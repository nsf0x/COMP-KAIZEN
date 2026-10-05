<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioResource\Pages;
use App\Models\Portfolio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PortfolioResource extends Resource
{
    protected static ?string $model = Portfolio::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Portofolio';

    protected static ?string $modelLabel = 'portofolio';

    protected static ?string $pluralModelLabel = 'portofolio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Portofolio')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul portofolio')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('type')
                            ->label('Tipe portofolio')
                            ->maxLength(100)
                            ->default('event')
                            ->required()
                            ->helperText('Isi tipe sesuai karya, misalnya Event, Dekorasi, atau Dokumentasi.'),

                        Forms\Components\DatePicker::make('event_date')
                            ->label('Tanggal acara'),

                        Forms\Components\TextInput::make('client_name')
                            ->label('Nama klien')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('video_url')
                            ->label('URL video')
                            ->url()
                            ->nullable()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Mendukung YouTube, Vimeo, atau URL file MP4, WebM, dan OGG. URL video yang sudah tersimpan tidak akan dihapus jika field dibiarkan kosong.'),

                        Forms\Components\FileUpload::make('video_path')
                            ->label('Upload video')
                            ->disk('public')
                            ->directory('portfolio/videos')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(102400)
                            ->helperText('Format MP4, WebM, atau OGG. Maksimal 100 MB. URL video di atas tetap tersedia.'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Foto utama')
                            ->image()
                            ->disk('public')
                            ->directory('portfolio')
                            ->imageEditor()
                            ->helperText('Upload foto utama portofolio.'),

                        Forms\Components\Repeater::make('images')
                            ->label('Galeri foto')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('portfolio/gallery')
                                    ->required(),
                                Forms\Components\TextInput::make('caption')
                                    ->label('Keterangan')
                                    ->maxLength(255)
                                    ->nullable(),
                                Forms\Components\TextInput::make('order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->orderColumn('order')
                            ->addActionLabel('Tambah foto')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan sebagai unggulan')
                            ->default(false),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn () => Portfolio::query()->whereNotNull('type')->distinct()->orderBy('type')->pluck('type', 'type')->all()),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Unggulan'),
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
            'index' => Pages\ListPortfolios::route('/'),
            'create' => Pages\CreatePortfolio::route('/create'),
            'edit' => Pages\EditPortfolio::route('/{record}/edit'),
        ];
    }
}
