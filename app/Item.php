<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $casts = [
        'dimension' => 'array',
    ];
    protected $fillable = [
        'name',
        'qty',
        'price',
        'list_price',
        'dimension',
        'available',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
//
//    public function getDimensionAttribute()
//    {
//        if(isset($this->dimension)) {
//            dd($this->dimension);
//        }
//        dd($this);
//        $this->attributes['dimension'] = '';
//        if(count($value) > 2) {
//            $this->attributes['dimension'] = $value['height'].'" / '.$value['width'].'" / '.$value['depth'].'"';
//        }
//    }
}
