<?php

namespace Paparee\BaleCms\App\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateFirebaseTokenController extends Controller
{
    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token' => $request->currentToken]);
            return response()->json([
                'success' => true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success' => false
            ],500);
        }
    }
}
