<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Category;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;
use App\Ticket;


class TicketsController extends Controller
{

// Remember to add this line 


public function create()
{
    $categories = Category::all();

    return view('tickets.create', compact('categories'));
}

public function store(Request $request, AppMailer $mailer)
{
    $this->validate($request, [
            'title'     => 'required',
            'category'  => 'required',
            'priority'  => 'required',
            'message'   => 'required'
        ]);

        $ticket = new Ticket([
            'title'     => $request->input('title'),
            'user_id'   => Auth::user()->id,
            'ticket_id' => strtoupper(str_random(10)),
            'category_id'  => $request->input('category'),
            'priority'  => $request->input('priority'),
            'message'   => $request->input('message'),
            'status'    => "Open",
        ]);

        $ticket->save();

        $mailer->sendTicketInformation(Auth::user(), $ticket);

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
}

}
