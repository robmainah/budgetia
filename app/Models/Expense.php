<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['name', 'title', 'type', 'description'];

    public function setSourceAttribute($value)
    {
        $this->attributes['source'] = ucfirst($value);
    }
}
