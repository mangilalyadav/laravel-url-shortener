<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'long_url',
        'code',
        'hits',
    ];

      protected $appends = [
        'short_url',
    ];

    public function getShortUrlAttribute()
    {
        return url($this->code);
    }
     public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
