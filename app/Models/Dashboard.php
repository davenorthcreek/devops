<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    public function assignments()
    {
        return $this->hasMany('App\Models\Assignment');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

}
