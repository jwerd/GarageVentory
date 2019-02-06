<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemMediaController extends Controller
{
    public function __invoke(Request $request, Item $item)
    {
        try {
            $item->addMedia($request->image)->toMediaCollection();
            
            return response()->json([
                'status'  => true,
                'message' => 'Media saved and uploaded!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error Uploading',
            ]);
        }
    }
}