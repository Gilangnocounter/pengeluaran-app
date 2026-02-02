<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $team = [
            [
                'name' => 'Adam Atma Wiguna',
                'desc' => 'NIM : 10123284',
                'photo' => '/images/adam.jpg'
            ],
            [
                'name' => 'Anggi Adnan Fauzi',
                'desc' => 'NIM : 10123264',
                'photo' => '/images/anggi.jpg'
            ],
            [
                'name' => 'M. Gilang Romadhon',
                'desc' => 'NIM : 10123288',
                'photo' => '/images/gilang.jpeg'
            ],
            [
                'name' => 'Taura Rahayudin',
                'desc' => 'NIM : 10123275',
                'photo' => '/images/taura.jpg'
            ],
        ];
        

        return view('about.index', compact('team'));
    }
}
