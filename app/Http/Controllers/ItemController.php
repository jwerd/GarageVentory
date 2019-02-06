<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ItemsCollection;
use App\Http\Resources\ItemResource;

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
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return new ItemsCollection($items);
        //return response()->json($items);
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

        $item = new Item;

        $item->name        = $request->name;
        $item->description = $request->description;
        $item->qty         = 1;
        $item->price       = $request->price;
        $item->list_price  = $request->list_price;
        $item->dimension   = $request->dimension;
        $item->available   = 1;
        $item->user_id     = Auth::id() ?? 1;
        
        $item->save();

        // $item = Item::create([
        //     'name'        => $request->name,
        //     'description' => $request->description,
        //     'qty'         => 1,
        //     'price'       => $request->price,
        //     'list_price'  => $request->list_price,
        //     'dimension'   => $request->dimension,
        //     'available'   => 1,
        //     'user_id'     => Auth::id() ?? 1,
        // ]);

        //$item->addMedia($request->image)->toMediaCollection();
        $data = [
            'data'    => new ItemResource($item),
            'status'  => (bool) $item,
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
        return new ItemResource($item);
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
            'description' => $request->description,
            'qty'         => 1,
            'price'       => $request->price,
            'list_price'  => $request->list_price,
            'dimension'   => $request->dimension,
            'user_id'     => Auth::id() ?? 1,
        ]);
        if(isset($request->image)) {
            $item->addMedia($request->image)->toMediaCollection();
        }
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
