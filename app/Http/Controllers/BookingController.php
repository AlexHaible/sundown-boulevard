<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class BookingController extends Controller
{
    public function getAll(): Collection
    {
        return Booking::all();
    }

    public function getSpecific(int $id): ?Booking
    {
        return Booking::find($id);
    }

    public function submitBooking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user'          => 'required|integer',
            'guests'        => 'required|integer',
            'timeslot'      => 'required|string',
            'date'          => 'required|date',
            'dish'          => 'required|string',
            'drink'         => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validated = (object) $validator->validated();

        $bookingUser        = $validated->user;
        $bookingGuests      = $validated->guests;
        $bookingTimeslot    = $validated->timeslot;
        $bookingDate        = $validated->date;
        $bookingDish        = $validated->dish;
        $bookingDrink       = $validated->drink;

        $booking = new Booking([
            'user_id'       => $bookingUser,
            'guests'        => $bookingGuests,
            'timeslot'      => $bookingTimeslot,
            'date'          => $bookingDate,
            'dish'          => $bookingDish,
            'drink'         => $bookingDrink,
        ]);
        $booking->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function checkAvailability(Request $request)
    {
        $guests             = $request->input('guests');
        $date               = $request->input('date');
        $tablesNeeded       = (int) ceil($guests / 2);
        $bookings           = Booking::whereDate('date', $date)->get();
        $tables             = app(GeneralSettings::class)->tables;
        $interval           = app(GeneralSettings::class)->intervals;
        $opens_at           = Carbon::parse(app(GeneralSettings::class)->opens_at);
        $closes_at          = Carbon::parse(app(GeneralSettings::class)->closes_at);
        $last_possible_time = $closes_at->copy()->subHours(2);
        $timeslot           = $opens_at->copy();

        while ($timeslot <= $last_possible_time) {
            $formattedTimeslot = $timeslot->format('H:i');
            $times[$formattedTimeslot] = 0; //Timeslot as key, reserved tables as value
            $timeslot->add($interval); //Onwards to the next timeslot, based on the interval from settings
        }

        foreach ($bookings as $booking) {
            $existingGuests = $booking->guests;
            $chosenTimeslot = $booking->timeslot;
            $tablesInUse = (int) ceil($existingGuests / 2); //Tables in use will always be half of guests, but rounded up, because 2 per table
            $leavesAt = Carbon::parse($chosenTimeslot)->addHours(2);
            $timeslot = Carbon::parse($chosenTimeslot);

            while ($timeslot < $leavesAt) {
                $formattedTimeslot = $timeslot->format('H:i');
                $times[$formattedTimeslot] += $tablesInUse; //Timeslot as key, reserved tables as value
                $timeslot->add($interval); //Onwards to the next timeslot, based on the interval from settings
            }
        }

        $data = []; //Init empty array, in case there's no available times
        foreach ($times as $key => $value) {
            if ($tables - $value >= $tablesNeeded) {
                $data[] = $key;
            }
        }

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
}
