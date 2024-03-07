<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function sendMail(Request $request)
    {
        try {
            $details = [
                'title' => $request->name . ' sending the message',
                'body' => 'Name: ' . $request->name . '<br> Email: ' . $request->email . '<br> Message: ' . $request->message,
                'reply' => $request->email,
            ];
    
            Mail::to("cs@esellexpress.com")
                ->send(new NotifMail($details));
    
            return back()->with('success', 'Sent successfully');
        } catch (\Exception $e) {
            // cek error
            // return response()->json(['error'=>$e->getMessage()]);
    
            // Return back with an alert for failure
            return back()->with('info', 'Email failed to send. Please try again.');
        }
    }
    
}
