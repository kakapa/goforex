<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Event;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Creates a new booking for logged in user using the event id
     *
     * @return \Illuminate\Http\Response
     */
    public function createEventBooking($eventId)
    {
        //
        $event = Event::where('id', $eventId)->first();

        $attendees = explode(',', $event->attendees);
        if(count($attendees) > 0 && $attendees[0] != ""){
            if (count($attendees) == $event->number_of_seats || $event->status_is == "FullyBooked"){
                flash("Sorry this event is fully booked.","error");
                return redirect('/home');
            }else{
                array_push($attendees, Auth::user()->id);
                $event->attendees = implode(',', $attendees);
                $event->save();
            }
        }else{
            $event->update(['attendees'=>Auth::user()->id]);
        }


        $booking = Booking::create(['user_id'=>Auth::user()->id,
                        'event_id'=>$eventId,
                        'reference'=>'BO'.str_random(9),
                        'status_is'=>'Pending']);


        $email = Auth::user()->email;
        $name = Auth::user()->username;

        $parameters = array(
            'username' => Auth::user()->username,
            'booking_ref' => $booking->reference,
            'booking_date_time' => $booking->created_date,
        );

        // Send email to show booking has been created
        Mail::send('emails.booking_created', $parameters, function ($message)
        use ($email, $name) {
            $message->from('noreply@goforex.co.za');
            $message->to($email, $name)->subject('GoForex - Booking Created');
        });

        flash("You have successfully created a booking, please make payment to get approval.","success");


        return redirect('/home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function approve($bookingId)
    {
        //
        $booking = Booking::where('id', $bookingId)->first();
        if ($booking){
            $booking->update(['status_is'=>'Paid']);

            $event = Event::where('id', $booking->event_id)->first();

            $attendees = explode(',', $event->attendees);

            $bookings = Booking::whereIn('user_id', $attendees)->where('event_id', $event->id)->get();

            flash("Booking approved successfully.", "success");

            $user = User::where('id', $booking->user_id)->first();

            $email = $user->email;
            $name = $user->username;

            $parameters = array(
                'username' => $user->username,
                'event_name' => $event->name,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'start_time' => $event->start_date,
                'address' => $event->address,
                'host' => $event->host,
                'booking_ref'=> $booking->reference,
            );
            // TODO add que

            // Send email to confirm successful registration
            Mail::send('emails.booking_confirmed', $parameters, function ($message)
            use ($email, $name) {
                $message->from('noreply@goforex.com');
                $message->to($email, $name)->subject('GoForex - Booking Confirmed');
            });


            return view('events.show', compact(['event', 'bookings']));

        }else {
            flash("The booking you are searching for doesn't exist.", "error");
        }
    }
}
