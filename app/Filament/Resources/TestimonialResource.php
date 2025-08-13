<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
use App\Models\SubKatalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Testimonial';

    protected static ?string $pluralModelLabel = 'Testimonial';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sub_katalog_id')
                    ->label('Sub Katalog')
                    ->options(function () {
                        return SubKatalog::with('katalog')
                            ->get()
                            ->mapWithKeys(function ($subKatalog) {
                                return [$subKatalog->id => $subKatalog->katalog->nama . ' - ' . $subKatalog->nama];
                            });
                    })
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('nama_project')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Project'),
                Forms\Components\TextInput::make('nama_client')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Client'),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->rows(4)
                    ->label('Deskripsi'),
                Forms\Components\TextInput::make('complete_hari')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->label('Complete (Hari)')
                    ->suffix('hari'),
                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('testimonials')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->size(60),
                Tables\Columns\TextColumn::make('subKatalog.katalog.nama')
                    ->label('Katalog')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subKatalog.nama')
                    ->label('Sub Katalog')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_project')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Project'),
                Tables\Columns\TextColumn::make('nama_client')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Client'),
                Tables\Columns\TextColumn::make('complete_hari')
                    ->sortable()
                    ->label('Complete (Hari)')
                    ->suffix(' hari')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(50)
                    ->label('Deskripsi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sub_katalog_id')
                    ->label('Sub Katalog')
                    ->options(function () {
                        return SubKatalog::with('katalog')
                            ->get()
                            ->mapWithKeys(function ($subKatalog) {
                                return [$subKatalog->id => $subKatalog->katalog->nama . ' - ' . $subKatalog->nama];
                            });
                    })
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Testimonial $record) {
                        // Delete image file when record is deleted
                        if ($record->gambar && Storage::disk('public')->exists($record->gambar)) {
                            Storage::disk('public')->delete($record->gambar);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function ($records) {
                            // Delete image files when records are bulk deleted
                            foreach ($records as $record) {
                                if ($record->gambar && Storage::disk('public')->exists($record->gambar)) {
                                    Storage::disk('public')->delete($record->gambar);
                                }
                            }
                        }),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
