<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekeningAdmin extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'rekening_admins';
    protected $primaryKey = 'id_rekening_admin';
    protected $hidden = ['created_at','updated_at'];

    public $timestamps = true;

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank','id_bank','id_bank');
    }
}
