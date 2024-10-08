<?php

namespace App\Filament\Resources\CompraResource\Pages;

use App\Filament\Resources\CompraResource;
use Filament\Actions;
use App\Models\Producto;
use Filament\Resources\Pages\CreateRecord;

class CreateCompra extends CreateRecord
{
    protected static string $resource = CompraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buscar el producto asociado
        $producto = Producto::findOrFail($data['producto_id']);

        // Asignar el precio unitario del producto
        $data['precio_unitario'] = $producto->precio;

        // Calcular el total basado en la cantidad y el precio unitario
        $data['total'] = $data['cantidad'] * $data['precio_unitario'];

        // Si hay otros campos a modificar o calcular, este es el lugar
        // Ejemplo: actualizar el stock del producto al recibir la compra
        $producto->stock += $data['cantidad'];
        $producto->save();

        return $data;
    }}
