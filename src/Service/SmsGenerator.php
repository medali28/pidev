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


        $accountSid = "AC54fe2b56cbcca91fea704d5e1b2830fe";  //Identifiant du compte twilio
        $authToken = "fa32ac7934a4a265fb2555de5981eacb"; //Token d'authentification
        $fromNumber = "+19497103822"; // Numéro de test d'envoie sms offert par twilio

        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' vous a envoyé le message suivant:'.' '.$text.''; //Contruction du sms

        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            '+21658602971',
            [
                'from' => '+19497103822',
                'body' => $message,
            ]
        );


    }
}


