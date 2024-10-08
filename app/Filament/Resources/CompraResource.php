<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompraResource\Pages;
use App\Filament\Resources\CompraResource\RelationManagers;
use App\Models\Compra;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('proveedor_id')
                ->relationship('proveedor', 'nombre')
                ->required(),
            Forms\Components\Select::make('producto_id')
                ->relationship('producto', 'nombre')
                ->required(),
            Forms\Components\TextInput::make('cantidad')
                ->required()
                ->numeric()
                ->reactive()
                ->afterStateUpdated(fn ($state, $get, callable $set) => 
                    $set('total', $state * $get('precio_unitario'))
                ),
            Forms\Components\TextInput::make('precio_unitario')
                ->required()
                ->numeric()
                ->reactive()
                ->afterStateUpdated(fn ($state, $get, callable $set) => 
                    $set('total', $state * $get('cantidad'))
                ),
            Forms\Components\TextInput::make('total')
                ->required()
                ->numeric()
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proveedor.nombre'),
                Tables\Columns\TextColumn::make('producto.nombre'),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('precio_unitario')->money('usd'),
                Tables\Columns\TextColumn::make('total')->money('usd'),
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
            'index' => Pages\ListCompras::route('/'),
            'create' => Pages\CreateCompra::route('/create'),
            'edit' => Pages\EditCompra::route('/{record}/edit'),
        ];
    }
}
