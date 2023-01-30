<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banks';
    protected $primaryKey = 'id_bank';
    protected $hidden = ['created_at','updated_at'];

    public $timestamps = true;

    public function rekening_admin()
    {
        return $this->belongsTo('App\Models\RekeningAdmin','id_bank','id_bank');
    }

    public function pengirim()
    {
        return $this->hasMany('App\Models\Transaksi','bank_pengirim','id_bank');
    }

    public function tujuan()
    {
        return $this->hasMany('App\Models\Transaksi','bank_tujuan','id_bank');
    }

}
