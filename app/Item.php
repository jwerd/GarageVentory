<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Item extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $casts = [
        'dimension' => 'array',
    ];

    public $incremeting = true;
    
    protected $fillable = [
        'name',
        'description',
        'qty',
        'price',
        'list_price',
        'price_sold',
        'dimension',
        'available',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(200)
              ->sharpen(10);
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
