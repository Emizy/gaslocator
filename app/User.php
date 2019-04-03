<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function closest( $lat, $lng, $max_distance = 25, $max_locations = 10, $units = 'kilometers')
    {
        /*
         *  Allow for changing of units of measurement
         */
        switch ( $units ) {
            default:
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 3959;
                break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
                break;
        }
        /*
         *  Generate the select field for disctance
         */
        $disctance_select = sprintf(
            "*, ( %d * acos( cos( radians(%s) ) " .
            " * cos( radians( lat ) ) " .
            " * cos( radians( lng ) - radians(%s) ) " .
            " + sin( radians(%s) ) * sin( radians( lat ) ) " .
            ") " .
            ") " .
            "AS distance",
            $gr_circle_radius,
            $lat,
            $lng,
            $lat
        );
        return $this
            ->select( DB::raw($disctance_select) )
            ->having( 'distance', '<', $max_distance )
            ->take( $max_locations )
            ->orderBy( 'distance', 'ASC' )
            ->get();
    }

}
