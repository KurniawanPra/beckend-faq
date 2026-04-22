<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $fillable = [
        'topik_id',
        'user_id',
        'pertanyaan',
        'jawaban_admin',
    ];

    protected $casts = [
        'topik_id' => 'integer',
        'user_id'  => 'integer',
    ];

    public function topik(): BelongsTo
    {
        return $this->belongsTo(Topik::class, 'topik_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
