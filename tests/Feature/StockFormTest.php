<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Mail\StockMail;
use Illuminate\Support\Facades\Mail;

class StockFormTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_form_submission_requires_all_fields()
    {
        $response = $this->post('/submit', []);

        $response->assertSessionHasErrors([
            'company', 'start_date', 'end_date', 'email'
        ]);
    }

    public function test_form_submission_requires_valid_dates()
    {
        $response = $this->post('/submit', [
            'start_date' => '2023-01-01',
            'end_date' => '2022-01-01',
        ]);

        $response->assertSessionHasErrors([
            'start_date', 'end_date'
        ]);
    }

    public function test_email_is_sent_on_successful_submission()
    {
        Mail::fake();

        $response = $this->post('/submit', [
            'company' => 'AMRN',
            'start_date' => '2023-01-01',
            'end_date' => '2023-01-31',
            'email' => 'test@example.com',
        ]);

        Mail::assertSent(StockInfoMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }
}
