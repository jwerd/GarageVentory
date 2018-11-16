<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $available = $request->has('showSoldItems') ? false : true;

        $items = Item::where('available', $available)
            ->latest()
            ->get()
            ->toArray();

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $item = Item::create([
            'name'        => $request->name,
            'qty'         => 1,
            'price'       => $request->price,
            'list_price'  => $request->list_price,
            'dimension'   => $request->dimension,
            'available'   => 1,
            'user_id'     => Auth::id() ?? 1,
        ]);

        $data = [
            'data' => $item,
            'status' => (bool) $item,
            'message' => $item ? 'Item Created!' : 'Error Creating Item',
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $status = $item->update([
            'name'        => $request->name,
            'qty'         => 1,
            'price'       => $request->price,
            'list_price'  => $request->list_price,
            'dimension'   => $request->dimension,
            'user_id'     => Auth::id() ?? 1,
        ]);

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Item Updated!' : 'Error Updating Item'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $status = $item->delete();

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Item Deleted!' : 'Error Deleting Item'
        ]);
    }
}
