<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'nama'=>$user->nama,
            'saldo'=>$user->saldo['saldo'],
        ]);   
    }

    public function profil()
    {
        $user = auth()->user();

        return response()->json([
            'id'=>$user->id,
            'nama'=>$user->nama,
            'telpon'=>$user->telpon,
            'email'=>$user->email,
            'alamat'=>$user->alamat,
            'saldo'=>$user->saldo['saldo'],
        ]);   
    }
}
