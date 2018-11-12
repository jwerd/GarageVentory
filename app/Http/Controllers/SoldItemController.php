<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoldItemController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $status = $item->update($request->only(['available', 'price_sold']));

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Item Updated!' : 'Error Updating Item'
        ]);
    }
}
