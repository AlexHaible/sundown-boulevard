<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getDrinks(int $number = 5): Collection
    {
        for ($x = 0; $x < $number; $x++) {
            $drinks[] = json_decode(Http::get('https://api.punkapi.com/v2/beers/random')->body())[0];
        }

        return collect($drinks);
    }

    public static function getDishes(int $number = 5): Collection
    {
        for ($x = 0; $x < $number; $x++) {
            $dishes[] = json_decode(Http::get('https://www.themealdb.com/api/json/v1/1/random.php')->body())->meals[0];
        }

        return collect($dishes);
    }

    public function booker()
    {
        return $this->belongsTo(User::class);
    }
}
