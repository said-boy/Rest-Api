<?php

namespace App\Http\Controllers;

use App\Models\Overtimes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OvertimesController extends Controller
{
    public function createOvertimes(Request $request)
    {
        $request->validate([
            'employee_id' => 'integer',
            'date' => 'date',
            'time_started' => 'date_format:H:i|before:time_ended',
            'time_ended' => 'date_format:H:i|after:time_started'
        ]);

        $overtimes = new Overtimes();
        $overtimes->employee_id = $request->employee_id;
        $overtimes->date = $request->date;
        $overtimes->time_started = $request->time_started;
        $overtimes->time_ended = $request->time_ended;
        $overtimes->save();

        return Overtimes::all();
    }

    public function showOvertimes(Request $request)
    {
        $request->validate([
            'time_started' => 'date_format:H:i|before:time_ended',
            'time_ended' => 'date_format:H:i|after:time_started'
        ]);

        $collection = DB::table('overtimes')
            ->select('overtimes.id','overtimes.employee_id', 'employees.name', 'overtimes.date', 'overtimes.time_started', 'overtimes.time_ended')
            ->from('overtimes')
            ->join('employees', 'overtimes.id', '=', 'employees.id')
            ->where('overtimes.time_started', $request['time_started'])
            ->where('overtimes.time_ended', $request['time_ended'])
            ->get();

        return $collection;

    }
}
