<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    protected $table = 'users';
    protected $primaryKey= "id";
    protected $keyType ='int';

    protected $fillable = [
        'name',
        'email',
        'password',
        'company'
    ];
    public $timestamps = true;
    public $incrementing = true;


    public function transaction():HasMany{
        return $this->hasMany(Transaction::class,"user_id", "id");
    }

    public function getAuthIdentifierName()
    {
       return 'email';
    }

    public function getAuthIdentifier()
    {
        return $this->email;
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
       return $this->token;
    }

    public function setRememberToken($value)
    {
        return $this->token=$value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }

}
