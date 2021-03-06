<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Booking;
use App\Event;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Requests\EventsFormRequest;
use Auth;
use PDF;

class EventsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'boss', 'profile']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $events = Event::orderBy('created_at','desc')->get();
        $bookings = Booking::whereIn('event_id', $events->pluck('id'))->select('id', 'user_id', 'event_id', 'status_is', 'created_at', 'updated_at')->get();

        return view('events.index', compact('events', 'bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $items=Item::get()->pluck('item_name', 'id');
        $statuses = Event::$statuses;
        $bank_accounts = BankAccount::pluck('account_holder', 'id');
        return view('events.create',compact('items', 'statuses', 'bank_accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventsFormRequest $request)
    {
        $request['attendees'] = '';

        $event = $request->all();
        $event['status_is'] = 'Pending';
        $event['reference'] = str_random(7);
        Event::create($event);
        flash('Event successfully created. Publish this event to make it public!', 'success');

        $events = Event::get();
        return redirect('events');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
        $bookings = Booking::where('event_id', $event->id)->get();
        return view('events.show', compact('event', 'bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
        $items=Item::get()->pluck('item_name', 'id');
        $statuses = Event::$statuses;
        $bank_accounts = BankAccount::pluck('account_holder', 'id');
        return view('events.edit', compact('event','items', 'statuses','bank_accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventsFormRequest $request, Event $event)
    {
        // Update the existing account
        $event->update($request->all());
        flash('Event has been updated!', 'success');
        return redirect('events');
    }

    /**
     * Update event status
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Event $event)
    {
        $event->update($request->all());

        flash('Status updated successfully', 'success');
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
        if ($event->status_is != 'Open'){
            // Delete an event
            $event->delete();
            flash('Event has been deleted!', 'success');
            $events = Event::get();
            $bookings = Booking::whereIn('event_id', $events->pluck('id'))->select('id', 'user_id', 'event_id', 'status_is', 'created_at', 'updated_at')->get();
            return view('events.index', compact('events', 'bookings'));
        }else{
            flash('This event is open, please update event status to either Pending OR Closed, and try deleting again!', 'error');
            return back();
        }
    }


    public function submitEvent($id){
        $event = Event::where('id',$id)->first();
        if ($event){
            $event->update(['status_is'=>'Open']);
            flash('Event is now open and members can book it!', 'success');
        }else{
            flash('The event you are looking for is invalid.', 'error');
        }

        $events = Event::orderBy('created_at','desc')->get();
//        $bookings = Booking::whereIn('event_id', $events->pluck('id'))->select('id', 'user_id', 'event_id', 'status_is', 'created_at', 'updated_at')->get();
        return redirect('/events');
    }

    /**
    *
    *
    *
    *
    */
    public function print_attendees(Event $event)
    {

        $bookings = Booking::where('event_id', $event->id)->select('id', 'event_id', 'user_id', 'status_is', 'created_at', 'updated_at')->get();

        // return view('pdf.attendee_register', compact('bookings', 'event'));
        $data=['bookings'=>$bookings, 'event'=>$event];
        $pdf = PDF::loadView('pdf.attendee_register', $data);

        return $pdf->download($event->start_date .' '. $event->start_time.'-attendee register- '.$event->name.'.pdf');
        
    }
    
}
