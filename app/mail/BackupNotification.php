<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupNotification extends Mailable
{
    use Queueable, SerializesModels;

    // Declare a public variable to store the backup file name
    public $fileName;

    // Constructor to initialize the file name
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    // Build the email content
    public function build()
    {
        // Return the view for the email and pass the file name to the view
        return $this->view('emails.backup_notification')
                    ->with([
                        'fileName' => $this->fileName,  // Pass the file name to the view
                    ])
                    ->subject('Database Backup Successful'); // Subject of the email
    }
}
