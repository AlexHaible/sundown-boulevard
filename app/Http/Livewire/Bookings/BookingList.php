<?php

namespace App\Http\Livewire\Bookings;

use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class BookingList extends Component
{
    public function render()
    {
        $request = Request::create('/api/bookings/all', 'GET');
        $result = Route::dispatch($request)->original;

        return view('livewire.bookings.booking-list')->with(compact('result'));
    }
}
