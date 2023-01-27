<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','value','jti','type','pair','payload'];
    protected $table = 'tokens';
    protected $primaryKey = 'id_token';
    protected $hidden = ['created_at','updated_at'];

    public $timestamps = true;

}
