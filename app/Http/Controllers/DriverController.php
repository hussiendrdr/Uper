<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
//        return $request->user()->driver;

       $user= $request->user();
       $user->load('driver');
       return $user;

    }
    public function update(Request $request)
    {
        $request->validate([
           'year'=>'required|numeric',
            'make'=>'required',
            'model'=>'required',
            'color'=>'required',
            'license_plate'=>'required',
            'name'=>'required',


        ]);
        $user=$request->user();
        $user->update($request->only('name'));

        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate',
        ]));
        $user->load('driver');
        return $user;
    }
}
