<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function transfer(Request $request)
    {
        $user = auth()->user();

        $reciever = $request->to;
        $ballance = $request->ballance;

        // cek saldo user, apakah mencukupi
        if($user->saldo['saldo'] < $ballance ) {
            return response()->json([
                'code'=>500,
                'msg' => 'Saldomu kurang bang',
            ]);
        }
        

        // cek reciever apakah ada di database
        $user_reciever = User::where('telpon', $reciever)->first();
        if( !$user_reciever ) {
            
            return response()->json([
               'code' => 500,
               'msg' => 'Nomor Penerima tidak ditemukan bang',
           ]);
        }
        // bikin transaksi
        $data = Transaksi::create([
            'from'=>$user->id,
            'to'=>$user_reciever->id,
            'ballance'=>$ballance,
            'time'=>date('Y-m-d H:i:s')
        ]);
        
        // update saldo user
        $saldo_reciever = Saldo::where('user_id', $user_reciever->id)->first();
        $saldo_reciever->update([
            'saldo'=> $saldo_reciever->saldo + $ballance,
        ]);

        // update saldo pengirim
        $saldo_sender = Saldo::where('user_id', $user->id)->first();
        $saldo_sender->update([
            'saldo' => $saldo_sender->saldo - $ballance,
        ]);

        
        return response()->json([
            'code'=>200,
            'msg' => $data,
        ]);
    }
    public function history()
    {
        $user = auth()->user();
        $user_id = auth()->user()->id;
        
        
        $data = Transaksi::where('from', $user_id)->orWhere('to', $user_id)->get()->sortBy('time');
        // dd($data);
        $history = [];
        foreach($data as $item) {
            
            // jika nomer pengirim = user id ==> pengirim / out
            if( $item->from == $user_id ) {
                // dd($item->to, $user->id);
                $reciever = User::where('id', $item->to)->first(); // cari penerimanya siapa
                
                array_push($history, ['type'=>'out', 'to'=>$reciever->nama,'ballance'=>$item->ballance, 'time'=>$item->time]);
            }
            // jika nomer tujuan = user id ==> menerima / in
            else if( $item->to == $user_id ) {

                $sender = User::where('id', $item->from)->first(); // cari pengirimnya siapa
                array_push($history, ['type'=>'in', 'from'=>$sender->nama,'ballance'=>$item->ballance, 'time'=>$item->time]);
            }
            
        }

        return response()->json([
            'code'=>200,
            'data'=>$history
        ]);
    }
}
