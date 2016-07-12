<?php namespace App\Models\Services;

use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
use Exception;
use App\Models\Traits\ResponseTraitService;

/**
 * Class that returns Array for JSON response
 */
class MailerService {
    
    /**
     * Response trait
     */
    use ResponseTraitService;

    /**
     * Check mail inbox and get mails.
     * 
     * @return array
     */
    public function getMails() {
        
        return $this->searchMails('UNSEEN');
    }
    
    /**
     * Search for mails
     * @param string $condition
     */
    private function searchMails( $condition ) {
        
        try {
            $mailbox = new ImapMailbox(
                    '{' . env('MAIL_HOST') . '}INBOX',
                    env('MAIL_USER'),
                    env('MAIL_PASS'),
                    __DIR__
            );

            $mailsIds = $mailbox->searchMailbox('UNSEEN');
        } catch (\Exception $e) {
            $mailsIds = false;
        }
        
        return $this->getMail( $mailsIds, $mailbox );
    }
    
    /**
     * Get mail from ids.
     * 
     * @param array $mailsIds
     */
    private function getMail( $mailsIds, $mailbox ) {
        
        $mail = false;
        
        if ($mailsIds) {
            $mailId = reset($mailsIds);
            $mail = $mailbox->getMail($mailId);
        }
        
        return $this->getOutput( $mail );
    }

    /**
     * Converting the given currency.
     * 
     * @param Request $request
     * @return JSON
     */
    private function getOutput( $response = false ) {
        
        if ($response) {
            $this->_response_data[] = $response;
        } else {
            $this->_errors_generic[] = 'Could not connect to mail server';
        }

        return $this->buildResponse();
    }
}
