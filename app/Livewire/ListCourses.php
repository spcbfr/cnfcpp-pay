<?php

namespace App\Livewire;

use App\Models\Course;
use GuzzleHttp\Client;
use Livewire\Component;

class ListCourses extends Component
{
    public function pay(Course $course)
    {
        $client = new Client([
            'base_uri' => 'https://test.clictopay.com/payment/rest',
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $response = $client->post('/register.do', [
            'form_params' => [
                'userName' => '123456789',
                'password' => '6E2OCk4sx',
                'orderNumber' => $course->id,
                'amount' => $course->price * 100, // Convert to cents
                'currency' => 788,
                'returnUrl' => 'https://yourwebsite.com/success',
                'language' => 'fr',
            ],
        ]);
        dd($response);

        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            $orderId = $responseData['orderId'];
            $formUrl = $responseData['formUrl'];

            // Redirect the user to the payment form
            return redirect()->away($formUrl);
        }

        // Handle the error response
        if ($response->getStatusCode() !== 200) {
            redirect('/login');
        }
    }
}
