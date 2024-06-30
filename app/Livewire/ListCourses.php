<?php

namespace App\Livewire;

use App\Models\Course;
use Http;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class ListCourses extends Component
{
    public function pay(Course $course): RedirectResponse
    {
        $res = Http::asForm()->post('https://test.clictopay.com/payment/rest/register.do', [
            'userName' => 'username',
            'password' => 'password',
            'orderNumber' => $course->id,
            'amount' => $course->price * 100,
        ]);

        $data = json_decode($res->body(), true);

        if ($data['errorCode'] === 0) {
            return redirect()->away($data['formUrl']);
        }
        session()->flash('failed_req', 'Your payment request failed, please try again later, REASON: '.$data['errorMessage']);

        return back();
    }
}
