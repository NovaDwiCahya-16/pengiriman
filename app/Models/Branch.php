<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['city', 'name', 'address'];

    public function requests()
    {
        return $this->hasMany(RequestModel::class);
    }
}
