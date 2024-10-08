<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono'];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }
    //use HasFactory;
}
