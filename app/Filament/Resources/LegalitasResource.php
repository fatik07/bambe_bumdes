<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LegalitasResource\Pages;
use App\Filament\Resources\LegalitasResource\RelationManagers;
use App\Models\Legalitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LegalitasResource extends Resource
{
    protected static ?string $model = Legalitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Legalitas';

    protected static ?string $modelLabel = 'Legalitas';

    protected static ?string $pluralModelLabel = 'Legalitas';
    protected static ?string $navigationGroup = 'Halaman';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Legalitas')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Legalitas')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar/Dokumen')
                            ->image()
                            ->disk('public')
                            ->directory('legalitas')
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
                            ->maxSize(2048) // 2MB
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Legalitas')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label('Gambar/Dokumen')
                            ->disk('public')
                            ->height(300)
                            ->width(400)
                            ->extraImgAttributes(['style' => 'object-fit: cover; border-radius: 8px;'])
                            ->columnSpanFull(),
                        
                        Infolists\Components\TextEntry::make('nama')
                            ->label('Nama Legalitas')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat pada')
                            ->dateTime('d M Y, H:i'),
                        
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diperbarui pada')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(60)
                    ->width(80)
                    ->extraImgAttributes(['style' => 'object-fit: cover;'])
                    ->defaultImageUrl('/images/placeholder.jpg'),
                
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Legalitas')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->alignment('center'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLegalitas::route('/'),
            'create' => Pages\CreateLegalitas::route('/create'),
            'edit' => Pages\EditLegalitas::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
