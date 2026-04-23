<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TargetAssignedMail extends Mailable
{
    public $employee;
    public $month;
    public $targetValue;

    public function __construct($employee, $month, $targetValue)
    {
        $this->employee = $employee;
        $this->month = $month;
        $this->targetValue = $targetValue;
    }

    public function build()
    {
        return $this->subject('New Monthly Target Assigned')
                    ->view('emails.target_assigned');
    }
}
