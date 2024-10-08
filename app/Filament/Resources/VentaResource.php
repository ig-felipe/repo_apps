<?php

namespace App\Filament\Resources;


use App\Filament\Resources\VentaResource\Pages;
use App\Filament\Resources\VentaResource\RelationManagers;
use App\Models\Venta;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->required(),
                Forms\Components\Select::make('producto_id')
                    ->relationship('producto', 'nombre')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $producto = Producto::find($state);
                            if ($producto) {
                                $set('precio_unitario', $producto->precio);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, callable $set) {
                        if ($state && $get('precio_unitario')) {
                            $set('total', $state * $get('precio_unitario'));
                        }
                    }),
                Forms\Components\TextInput::make('precio_unitario')
                    ->required()
                    ->numeric()
                    ->disabled(),
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
                Tables\Columns\TextColumn::make('cliente.nombre'),
                Tables\Columns\TextColumn::make('producto.nombre'),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('precio_unitario')->money('usd'),
                Tables\Columns\TextColumn::make('total')->money('usd'),
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
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
        ];
    }
    protected static function beforeCreate(array $data): array
    {
        $producto = Producto::findOrFail($data['producto_id']);
        $data['precio_unitario'] = $producto->precio;
        $data['total'] = $data['cantidad'] * $data['precio_unitario'];
        return $data;
    }

    public static function afterCreate(Model $record): void
    {
        $producto = $record->producto;
        $producto->stock -= $record->cantidad;
        $producto->save();
    }

    public static function afterDelete(Model $record): void
    {
        $producto = $record->producto;
        $producto->stock += $record->cantidad;
        $producto->save();
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $producto = Producto::findOrFail($data['producto_id']);
        $data['precio_unitario'] = $producto->precio;
        $data['total'] = $data['cantidad'] * $data['precio_unitario'];
        return $data;
    }

}
