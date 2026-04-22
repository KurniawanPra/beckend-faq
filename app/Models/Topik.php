<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topik extends Model
{
    use HasFactory;

    protected $table = 'topik';

    protected $fillable = [
        'nama',
    ];

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'topik_id');
    }
}
