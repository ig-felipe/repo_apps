<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock'];

    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class);
    }

    public function proveedores(): BelongsToMany
    {
        return $this->belongsToMany(Proveedor::class);
    }
}
