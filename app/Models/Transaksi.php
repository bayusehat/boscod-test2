<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'transaksi_transfers';
    protected $primaryKey = 'id_transaksi';
    protected $hidden = ['created_at','updated_at'];

    public $timestamps = true;
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User','id_user');
    }

    public function pengirim()
    {
        return $this->belongsTo('App\Models\RekeningAdmin','bank_pengirim');
    }

    public function tujuan()
    {
        return $this->belongsTo('App\Models\Bank','bank_tujuan');
    }
}
