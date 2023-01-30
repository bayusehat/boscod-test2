<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Bank;
use App\Models\RekeningAdmin;
use Validator;

class TransaksiController extends Controller
{
    public function createTransfer(Request $request)
    {
        $rules = [
            'nilai_transfer' => 'required',
            'bank_tujuan' => 'required',
            'rekening_tujuan' => 'required',
            'bank_pengirim' => 'required'
        ];

        $isValid = Validator::make($request->all(), $rules);

        if($isValid->fails())
            return response(['errors' => $isValid->errors()],422);

        $kode_unik = rand(100,999);
        $transaksi = new Transaksi;
        $transaksi->id_transaksi = $this->generateId();
        $transaksi->bank_pengirim = $this->getBank($request->input('bank_pengirim'))->id_bank;
        $transaksi->bank_tujuan = $this->getBank($request->input('bank_tujuan'))->id_bank;
        $transaksi->rekening_tujuan = $request->input('rekening_tujuan');
        $transaksi->atasnama_tujuan = $request->input('atasnama_tujuan');
        $transaksi->kode_unik = $kode_unik;
        $transaksi->nilai_transfer = $request->input('nilai_transfer');
        $transaksi->total_transfer = $request->input('nilai_transfer') + $kode_unik;
        $transaksi->id_user = auth('api')->user()->id;
        if($transaksi->save())
            return $this->getTransaksi($transaksi->id_transaksi);
        
        return response(['error' => 'Cannot create Transfer!'],500);
    }

    protected function generateId(){
        $record = Transaksi::latest()->first();
        $pattern = 'TF'.date('dmy');
        if (!$record) {
            $id = $pattern.sprintf('%05d',1);
        }else{
            $expNum = substr($record->id_transaksi,-5);
            $inc = ((int) $expNum + 1);
            $id = $pattern.sprintf('%05d', $inc);
        }

        return $id;
    }

    protected function getBank($nama_bank){
        $getId = Bank::where('nama_bank',$nama_bank)->first();
        return $getId;
    }

    protected function getTransaksi($id){

        $transaksi = Transaksi::select('id_transaksi', 'nilai_transfer', 'kode_unik', 'total_transfer', 'c.nama_bank as bank_perantara','b.nomor_rekening as rekening_perantara')
        ->join('rekening_admins as b','transaksi_transfers.bank_pengirim','=','b.id_bank')
        ->join('banks as c','b.id_bank','=','c.id_bank')
        ->find($id);
        $transaksi->expired_transfer = date('Y-m-d H:i:s',strtotime('+1 hour',$transaksi->created_at));
        if($transaksi->save())
            return response($transaksi,200);
        
        return response(['error' => 'Not found!'], 404);
    }

    public function listBank()
    {
        $bank = Bank::all();
        return response($bank);
    }
}
