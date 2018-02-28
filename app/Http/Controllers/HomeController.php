<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('master');
    }

    public function find(Request $request){
        $query = $request->get('search');
        if (isset($query)) {
            $tasks = Product::search($query);
            if (! count($tasks) == 0) {
                return view('shop.index')
                    ->withDetails($tasks)
                    ->withQuery($query);
            }
        }
        return view('shop.index',[
            'unsuccess_message' => 'No results!',
        ]);
    }

    public function contacts(){
        return view('shop.contacts.index');
    }
}