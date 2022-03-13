<?php

namespace App\Http\Controllers;

use App\Models\References;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $result = References::select('code', 'id', 'expression')->where(['id' => $request['overtime_method'], 'code' => 'overtime_method'])->get();
        Settings::where('key', 'overtime_method')
            ->update([
                'value' => $result[0]['id'],
                'expression' => $result[0]['expression']
            ]);
        return Settings::all();
    }
}
