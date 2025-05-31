<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey= "id";
    protected $keyType ='int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'tanggalTransaksi',
        'jenisTransaksi',
        'nominal',
        'deskripsi',
        'user_id'
    ];


    public function user(): BelongsTo{
        return $this->belongsTo(User::class,"user_id", "id");
    }
}
