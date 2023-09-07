<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'username', 'password', 'name'
    ];
    public $timestamps = true;
    public $incrementing = true;



    public function contacts() {
        return $this->hasMany(Contact::class, "user_id","id");
    }
}
