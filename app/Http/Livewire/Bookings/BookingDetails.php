<?php

namespace App\Http\Livewire\Bookings;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class BookingDetails extends Component
{
    public function render()
    {
        $request = Request::create('/api/bookings/' . request()->id, 'GET');
        $result = Route::dispatch($request);

        return view('livewire.bookings.booking-details')->with(compact('result'));
    }
}
