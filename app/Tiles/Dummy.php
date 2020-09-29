<?php

namespace App\Tiles;

use Livewire\Component;

class Dummy extends Component
{

    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('tiles.dummy', [
            'data' => \Spatie\Dashboard\Models\Tile::firstOrCreateForName('dummy')->getData('my_data')
        ]);
    }
}
