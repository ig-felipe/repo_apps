<?php

namespace App\Filament\Resources\VentaResource\Pages;

use App\Filament\Resources\VentaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Producto;

class CreateVenta extends CreateRecord
{
    protected static string $resource = VentaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $producto = Producto::findOrFail($data['producto_id']);
        $data['precio_unitario'] = $producto->precio;
        $data['total'] = $data['cantidad'] * $data['precio_unitario'];
        return $data;
    }
}
