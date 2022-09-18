<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

/**
 * @deprecated Privilégié d'utiliser le service App\Service\MailjetService
 */
class Mail
{
    private $api_key = 'b4538591c17c6a5e8c57b9b152eb7e85';
    private $api_key_secret = '47459706317fb9fff92f2e0c292b6d36';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@liligiroud.fr",
                        'Name' => "Lili Giroud - Massage madérothérapeutique"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4208274,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
