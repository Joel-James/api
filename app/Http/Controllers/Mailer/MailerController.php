<?php namespace App\Http\Controllers\Mailer;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Services\MailerService;

class MailerController extends Controller {
    
    /**
     * Check mail inbox and get mails.
     * 
     * @return array
     */
    public function getMails() {
        
        $mail = new MailerService();
        
        return $mail->getMails();
    }
}
