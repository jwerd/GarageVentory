<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Scopes\ItemScope;
use Illuminate\Support\Facades\Auth;

class Item extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $casts = [
        'dimension' => 'array',
        'sold_on'   => 'datetime',
    ];

    public $incremeting = true;
    

    protected $fillable = [
        'name',
        'description',
        'qty',
        'price',
        'list_price',
        'price_sold',
        'sold_on',
        'dimension',
        'available',
        'user_id',
    ];

    protected static function boot() 
    {
        parent::boot();

        static::addGlobalScope(new ItemScope);
    }

    public static function createFromRequest($request)
    {
        $request = array_merge($request, [
            'user_id' => Auth::id()
        ]);

        return Item::create($request);
    }
    
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    public function registerMediaConversions(Media $media = null) 
    {
        $this->addMediaConversion('thumb')
              ->width(200)
              ->sharpen(10);
    }

    public function getSoldOnAttribute($value)
    {
        // Return the sold_on if set
        return $value ?? $this->updated_at;
    }
}
