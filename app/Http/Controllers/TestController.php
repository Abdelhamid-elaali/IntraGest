<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    /**
     * Display a generic test page.
     * This can be used as a redirect target after triggering an alert.
     */
    public function index()
    {
        return view('test.index'); // We'll need to create this view later
    }

    /**
     * Abort with a specific HTTP error code.
     *
     * @param  int  $errorCode
     * @return \Illuminate\Http\Response
     */
    public function showErrorPage($errorCode)
    {
        $validErrorCodes = [401, 402, 403, 404, 500, 503];
        if (!in_array((int)$errorCode, $validErrorCodes)) {
            abort(404); // Default to 404 if an invalid code is provided
        }
        abort((int)$errorCode);
    }

    /**
     * Flash an alert message of a specific type and redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $alertType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showAlert(Request $request, $alertType)
    {
        $validAlertTypes = ['info', 'success', 'warning', 'error', 'primary', 'secondary'];
        if (!in_array($alertType, $validAlertTypes)) {
            Session::flash('alert', [
                'type' => 'error',
                'message' => 'Invalid alert type specified for testing.',
                'title' => 'Test Error'
            ]);
            return Redirect::back()->withInput();
        }

        $title = ucfirst($alertType) . ' Test Alert';
        $message = 'This is a test ' . $alertType . ' alert message.';

        Session::flash('alert', [
            'type' => $alertType,
            'message' => $message,
            'title' => $title
        ]);
        
        // Redirect to a generic test page or back to the previous page
        // For now, let's redirect back. If a dedicated test page is made, change to:
        // return redirect()->route('test.index'); 
        return Redirect::back();
    }
}
