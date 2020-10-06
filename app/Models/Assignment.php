<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public function tile()
    {
        return $this->hasOne("\App\Models\AvailableTile", 'id', 'tile_id');
    }

    public function dashboard()
    {
        return $this->belongsTo("\App\Models\Dashboard");
    }

    public function toHtml()
    {
        $name = $this->tile->name;
        $pos = $this->position;
        return "<livewire:$name position=\"$pos\" />";
    }
}
