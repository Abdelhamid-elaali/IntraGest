<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    private $errorMessages = [
        401 => [
            'title' => 'Unauthorized Access',
            'message' => 'Sorry, you are not authorized to access this page. Please make sure you have the necessary permissions.'
        ],
        402 => [
            'title' => 'Payment Required',
            'message' => 'Sorry, this action requires payment. Please complete the payment process to continue.'
        ],
        403 => [
            'title' => 'Access Forbidden',
            'message' => 'Sorry, you don\'t have permission to access this resource. Please contact your administrator if you believe this is a mistake.'
        ],
        404 => [
            'title' => 'Page Not Found',
            'message' => 'Sorry, the page you are looking for could not be found. It might have been moved, deleted, or never existed.'
        ],
        500 => [
            'title' => 'Server Error',
            'message' => 'Oops! Something went wrong on our end. Our team has been notified and we\'re working to fix the issue.'
        ],
        503 => [
            'title' => 'Service Unavailable',
            'message' => 'Sorry, our service is temporarily unavailable. We\'re performing maintenance or experiencing technical difficulties. Please try again later.'
        ]
    ];

    public function show($code)
    {
        if (!array_key_exists($code, $this->errorMessages)) {
            $code = 404;
        }

        return view('errors.generic', [
            'code' => $code,
            'title' => $this->errorMessages[$code]['title'],
            'message' => $this->errorMessages[$code]['message']
        ]);
    }
} 