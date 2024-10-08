<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('descripcion')
                ->required()
                ->maxLength(65535),
            Forms\Components\TextInput::make('precio')
                ->required()
                ->numeric()
                ->prefix('$'),
            Forms\Components\TextInput::make('stock')
                ->required()
                ->numeric(),
            Forms\Components\MultiSelect::make('clientes')
                ->relationship('clientes', 'nombre'),
            Forms\Components\MultiSelect::make('proveedores')
                ->relationship('proveedores', 'nombre'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('nombre'),
            Tables\Columns\TextColumn::make('descripcion')->limit(50),
            Tables\Columns\TextColumn::make('precio')->money('usd'),
            Tables\Columns\TextColumn::make('stock'),
            Tables\Columns\TextColumn::make('clientes_count')->counts('clientes')->label('Clientes'),
            Tables\Columns\TextColumn::make('proveedores_count')->counts('proveedores')->label('Proveedores'),
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
