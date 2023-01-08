<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }
    public function create_otp(Request $request) 
    {
        
        $kode = random_int(100000, 999999);
        Otp::updateOrCreate([
            'telpon'=>$request->telpon
            ],[
            'kode'=>$kode,
        ]);

        // // kirim pake sms nexmo
        // $basic  = new \Nexmo\Client\Credentials\Basic('0c4da485', '2xicRkYF0tc1KFpH');
        // $client = new \Nexmo\Client($basic);    
 
        // $client->message()->send([
        //     'to' => '62859160110138',
        //     'from' => 'Sugih Wallet',
        //     'text' => 'OTP mu bangg : '.$kode,
        // ]);

        return response()->json([
            'kode'=>200,
            'msg'=>'Otp udah dikirim bang'
        ]);

    }
    public function validate_otp($telpon, $kode) 
    {
        
        $data = Otp::where('telpon', $telpon)->first();
        
        if( !$data ) return response()->json(['code'=>500, 'msg'=>'Nomor belum terdaftar bang']);

        if($data->kode == $kode) {
            return 200;
        } else {
            return 500;
        }
    }

    public function register(Request $request)
    {
        if( $this->validate_otp($request->telpon, $request->kode) !== 200 ) {
            return response()->json([
                'code'=>500,
                'msg'=>'Otp salah bang, ulang lgi'
            ]);
        }
        
        $user = User::create([
            'nama'=>$request->nama,
            'email'=>$request->email,
            'telpon'=>$request->telpon,
            'password'=>Hash::make($request->password),
            'alamat'=>$request->alamat,
        ]);
        Saldo::create([
            'user_id'=>$user->id,
            'saldo'=>0
        ]);
        

        return response()->json([
            'code'=>200,
            'msg' => $user
        ]);
    }

    public function login(Request $request)
    {

        $data = request(['email','password']);
        
        Auth::attempt($data);
        // dd(Auth::check(), $data);

        if( Auth::check() ) {
            
            return response()->json([
                'code'=>200,
                'msg' => 'Login berhasil bang!'
            ]);

        } else { 

            return response()->json([
                'code'=>500,
                'msg' => 'Username Password salah bang!'
            ]);
        }
    }
}
