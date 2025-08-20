<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubKatalogResource\Pages;
use App\Filament\Resources\SubKatalogResource\RelationManagers;
use App\Models\SubKatalog;
use App\Models\Katalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class SubKatalogResource extends Resource
{
    protected static ?string $model = SubKatalog::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationLabel = 'Sub Katalog';

    protected static ?string $pluralModelLabel = 'Sub Katalog';
    protected static ?string $navigationGroup = 'Katalog';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('katalog_id')
                    ->label('Katalog')
                    ->options(Katalog::all()->pluck('nama', 'id'))
                    ->required()
                    ->searchable()
                    ->columnSpan(1),
                Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Sub Katalog')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, string $state, Forms\Set $set) {
                            if ($operation !== 'create') {
                                return;
                            }
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->label('Slug')
                        ->unique(ignoreRecord: true)
                        ->rules(['alpha_dash'])
                        ->disabled(),
                ]),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->rows(4)
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('sub_katalogs')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                    ->maxSize(2048)
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                        '3:4',
                        '9:16',
                    ])
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('1200')
                    ->imageResizeTargetHeight('1200')
                    ->helperText('Upload gambar dengan format JPG, PNG, atau GIF. Maksimal ukuran file 2MB.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->size(60),
                Tables\Columns\TextColumn::make('katalog.nama')
                    ->label('Katalog')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Sub Katalog'),
                // Tables\Columns\TextColumn::make('slug')
                //     ->searchable()
                //     ->sortable()
                //     ->label('Slug')
                //     ->badge()
                //     ->color('success'),
                // Tables\Columns\TextColumn::make('deskripsi')
                //     ->limit(50)
                //     ->label('Deskripsi'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->label('Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('katalog_id')
                    ->label('Katalog')
                    ->options(Katalog::all()->pluck('nama', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (SubKatalog $record) {
                        // Delete image file when record is deleted
                        if ($record->image && Storage::disk('public')->exists($record->image)) {
                            Storage::disk('public')->delete($record->image);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function ($records) {
                            // Delete image files when records are bulk deleted
                            foreach ($records as $record) {
                                if ($record->image && Storage::disk('public')->exists($record->image)) {
                                    Storage::disk('public')->delete($record->image);
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
            'index' => Pages\ListSubKatalogs::route('/'),
            'create' => Pages\CreateSubKatalog::route('/create'),
            'edit' => Pages\EditSubKatalog::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
