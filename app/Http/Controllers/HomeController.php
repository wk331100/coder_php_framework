<?php

namespace App\Http\Controllers;

use System\Request;
use System\Response;

class HomeController extends Controller {

    public function index(Request $request){
        $params = $request->method();
        return Response::json($params);
    }

    public function upload(Request $request){
        $params = $request->all();
        $data = [];
        if($request->hasFile('image')){
            $file = $request->file('image');
            $data = [
                'path' => $file->path(),
                'extension' => $file->extension(),
                'type' => $file->getType(),
                'size' => $file->getClientSize(),
                'name' => $file->getClientOriginalName(),
                'new_path' => $file->move(env('UPLOAD_PATH'))
            ];


        }

        return Response::json($data);
    }

}