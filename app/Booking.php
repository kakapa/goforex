<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'reference',
        'user_id',
        'event_id',
        'proof_of_payment',
        'status_is',
    ];


    /**
     * The array of $statuses.
     *
     * @var array
     */
    public static $statuses = [
        'Paid' => 'Paid',
        'Pending' => 'Pending',
        'Rejected' => 'Rejected',
    ];

    // A Booking belongsTo User
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
