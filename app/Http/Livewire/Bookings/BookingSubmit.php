<?php

namespace App\Http\Livewire\Bookings;

use Carbon\Carbon;
use App\Models\Booking;
use Livewire\Component;
use App\Settings\GeneralSettings;
use Spatie\LaravelSettings\Settings;

class BookingSubmit extends Component
{
    public function render()
    {
        $tables = app(GeneralSettings::class)->tables;
        $interval = app(GeneralSettings::class)->intervals;
        $opens_at = Carbon::parse(app(GeneralSettings::class)->opens_at);
        $closes_at = Carbon::parse(app(GeneralSettings::class)->closes_at);
        $last_possible_time = $closes_at->copy()->subHours(2);
        $timeslot = $opens_at->copy();

        while ($timeslot <= $last_possible_time) {
            $times[] = $timeslot->format('H:i');
            $timeslot->add($interval);
        }

        $dishes = Booking::getDishes(); //Default is 5, number can be added.
        $drinks = Booking::getDrinks(); //Default is 5, number can be added.

        return view('livewire.bookings.booking-submit')->with(compact('tables', 'times', 'dishes', 'drinks'));
    }
}
