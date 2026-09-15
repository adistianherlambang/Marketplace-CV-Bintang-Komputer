<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahans';
    protected $fillable = ['kecamatan_id', 'nama_kelurahan'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}