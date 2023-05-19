<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanSubmitRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index() {
        return view("index", [  ]);
    }

    public function submit( LoanSubmitRequest $req ) {
        print_r( $req->all() );
    }

}
