<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanFromUser extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_from_user';

    protected $fillable = [
        'nama_user',
        'email',
        'jabatan',
        'bagian',
        'pertanyaan',
        'jawaban_melalui',
        'nomor_kontak',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
