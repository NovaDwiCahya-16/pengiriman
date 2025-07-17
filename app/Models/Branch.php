<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['location', 'city'];

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
