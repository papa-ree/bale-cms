<?php

namespace Paparee\BaleCms\App\Livewire;

use App\Http\Controllers\Controller;
// use App\Traits\ApiServiceTrait;
use Illuminate\Http\Request;

class UpdateFirebaseToken extends Controller
{
    // use ApiServiceTrait;

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
