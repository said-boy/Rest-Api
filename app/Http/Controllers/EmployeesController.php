<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\References;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        
        $request->validate([
            'name' => 'string|min:2|unique:employees',
            'status_id' => 'integer',
            'salary' => 'integer|min:2000000|max:10000000'
        ]);
        
        $res = References::select('id', 'code')->where([
            'id' => $request->status_id,
            'code' => 'employee_status'
        ])->get();

        $employees = new Employees();
        $employees->name = $request->name;
        $employees->status_id = $res[0]['id'];
        $employees->salary = $request->salary;
        $employees->save();

        return "berhasil menambahkan data";
    }

    public function show(Request $request)
    {

        $request->validate([
            'per_page' => 'integer',
            'page' => 'integer',
            'order_by' => 'string',
            'order_type' => 'string'
        ]);

        if ($request['per_page']) {
            $collection = DB::table('employees')
                ->select('employees.*', 'references.id')
                ->from('employees')
                ->join('ujian_laravel.references', 'employees.status_id', '=', 'references.name')
                ->paginate(10);
            return $collection;
        } elseif ($request['page']) {
            $collection = DB::table('employees')
                ->select('employees.*', 'references.id')
                ->from('employees')
                ->join('ujian_laravel.references', 'employees.status_id', '=', 'references.name')
                ->paginate(1);
            return $collection;
        }

        if($request['order_by'] == 'name' ){
            return Employees::orderBy('name', 'asc')->get();
        } elseif($request['order_by'] == 'salary'){
            return Employees::orderBy('salary', 'asc')->get();
        }

        if($request['order_type'] == 'ASC'){
            return Employees::orderBy('status_id','asc')->get();
        } elseif ($request['order_type'] == 'DESC') {    
            return Employees::orderBy('status_id','desc')->get();
        }

        // return $request;
    }
}
