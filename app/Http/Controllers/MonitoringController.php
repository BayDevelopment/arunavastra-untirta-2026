<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class MonitoringController extends Controller
{
    public function index()
    {
        return view('monitoring.index', [
            'raspberryIp' => '192.168.0.180',
        ]);
    }
}
