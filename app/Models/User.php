<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey= "id";
    protected $keyType ='int';
    public $timestamps = true;
    public $incrementing = true;

    public function transaction():HasMany{
        return $this->hasMany(Transaction::class,"user_id", "id");
    }
}
