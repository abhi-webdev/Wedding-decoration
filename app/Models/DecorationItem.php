<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'decoration_id',
        'name',
        'description',
        'quantity',
    ];

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }
}
