<?php

namespace App\Http\Controllers;

use App\Models\log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Slim\Route;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //\var_dump( log::all());
        return Inertia::render("crud" , [
            "users" => log::paginate(10)

        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' =>  'required|max:50',
            'email' =>  'required|email',
            'address' =>  'required',
            'phone' =>  'required|max:30|min:3',
        ]);
        log::create($validate);
        return Inertia::location('crud');
 
    }

    /**
     * Display the specified resource.
     */
    public function show(log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(log $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, log $user)
    {
        $validate = $request->validate([
            'name' =>  'required|max:50',
            'email' =>  'required|email',
            'address' =>  'required',
            'phone' =>  'required|max:30|min:10',
        ]);
        $user->update($validate);
       
        return Inertia::location('crud');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ids)
    {
        $ids = explode(',',$ids);
        $arr = [...$ids];
       if($arr[0] == "all" ) {
            log::where('id', '!=' , null)->delete();
        } else {
            log::whereIn('id', $arr)->delete();
        }
        return Inertia::location('crud');
    }
}
