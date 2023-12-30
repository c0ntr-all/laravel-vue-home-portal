<?php

namespace App\Models\Restaurants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Restaurants\RestaurantVisit
 *
 * @property int $id
 * @property int $user_id
 * @property int $restaurant_id
 * @property string $datetime
 * @property mixed $food
 * @method static \Database\Factories\Restaurants\RestaurantVisitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit query()
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit whereFood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestaurantVisit whereUserId($value)
 * @mixin \Eloquent
 */
class RestaurantVisit extends Model
{
    use HasFactory;
}
