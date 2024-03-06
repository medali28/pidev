<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use Twilio\Rest\Client;
use App\Entity\ForbiddenKeyword;
use App\Entity\Medicament;
use App\Form\AjoutmedType;



class SmsGenerator
{
    public function SendSms(string $number, string $name, string $text)
    {


        $accountSid = "AC86c69b8faee8d6d1dd19b977c214e010";  //Identifiant du compte twilio
        $authToken = "13a682aa50bd24f3ccf8336a14ccf6e9"; //Token d'authentification
        $fromNumber = "+12406182985"; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
                'from' => '+12406182985',

                'body' => $message,
            ]
        );


    }
}


