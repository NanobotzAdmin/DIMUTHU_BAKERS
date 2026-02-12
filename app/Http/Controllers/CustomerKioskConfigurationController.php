<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerKioskConfigurationController extends Controller
{
    public function customerKioskConfigurationIndex()
    {
        return view('customerKiosk.configuration');
    }
}
