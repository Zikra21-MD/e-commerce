<?php
namespace App\Models;

use Database\Seeders\kategori;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'kategori_id',
        'wilayah_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class);
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'id');
    }
}
