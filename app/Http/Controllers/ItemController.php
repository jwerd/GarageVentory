<?php

namespace App\Http\Controllers;

use App\Item;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ItemsCollection;
use App\Http\Resources\ItemResource;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemStoreRequest $request)
    {
        $item = Item::createFromRequest($request->validated());

        $data = [
            'data'    => $item,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(ItemUpdateRequest $request, Item $item)
    {
        $status = $item->update($request->validated());

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
