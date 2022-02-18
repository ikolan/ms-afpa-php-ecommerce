<?php

namespace App\Data;

use Mailjet\Client as MailjetClient;
use Mailjet\Resources;

class Mailing
{
    private $apiKey = "35d8be4a8fa08d5a0f0b8f7e3e97eb41";
    private $secretKey = "5008499be73327ea529c15a3f0dc0cd7";

    public function send(int $template, string $toEmail, string $toName, string $subject, array $variables)
    {
        $mj = new MailjetClient($this->apiKey, $this->secretKey, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "jeanmarkdurand@gmail.com",
                        'Name' => "Jean"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => $template,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => $variables
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
