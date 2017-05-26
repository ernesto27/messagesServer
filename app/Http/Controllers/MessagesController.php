<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessagesController extends Controller
{
    private $validator;

    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {
        if($this->validateError($request)){
            $response = [
                'status' => 'error',
                'errors' => [$this->validator->errors()]
            ];
            return response()->json($response, 422);
        }

        $message = Message::create([
            'body' => $request->get('body')
        ]);

        if($message){
            return ['status' => 'ok'];
        }

        return ['status' => 'error'];
    }

    public function update($id, Request $request)
    {
        if($this->validateError($request)){
            $response = [
                'status' => 'error',
                'errors' => [$this->validator->errors()]
            ];
            return response()->json($response, 422);
        }

        $message = Message::find($id);
        if($message){
            $message->body = $request->get('body');
            $message->save();
            return ['status' => 'ok'];
        }

        return response()->json(['status' => 'error', 'message' => 'Not found message'], 404);
    }

    protected function validateError($request)
    {
        $this->validator = \Validator::make($request->all(), ['body' => 'required']);
        if ($this->validator->fails()) {
            return true;
        }
    }

}
