<?php

namespace App\Service;

use Mailjet\Resources;
use Mailjet\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailjetService
{

  private $params;

  public function __construct(ParameterBagInterface $params)
  {
      $this->params = $params;
  }
  
  public function sendEmail(string $email, string $name, string $sujet, string $contenu): void
  {
    $secret = $this->params->get('app.mailjet.secret');
    $key = $this->params->get('app.mailjet.key');
    $mj = new Client(
      $key, 
      $secret, 
      true, 
      ['version' => 'v3.1']
    );
    
    $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "secoubafofana@hotmail.fr",
          'Name' => "Lili Giroud - Massage madérothérapeutique"
        ],
        'To' => [
          [
            'Email' => $email,
            'Name' => $name
          ]
        ],
        'Subject' => $sujet,
        'HTMLPart' => $contenu,
      ]
    ]
  ];
  $response = $mj->post(Resources::$Email, ['body' => $body]);
  $response->success();
  }
}