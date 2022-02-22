<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['source', 'amount', 'date', 'category_id', 'description'];

    public function setSourceAttribute($value)
    {
        $this->attributes['source'] = ucfirst($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
