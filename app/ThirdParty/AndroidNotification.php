<?php

namespace App\ThirdParty;

use Edujugon\PushNotification\PushNotification;

trait AndroidNotification
{
    private $message = [];
    private $devices = [];
    private $sound = '1';
    private $payload = [];
    private $credentials = "AAAAoEGDseg:APA91bHzxe0V65A2MFbalaEuRH-0_SWacv81IIOVzY6iBI3WO7d-_ajC_uJdBUdPfdkkio9EgOWbdNsOkyBqyB_sDezYyrFLFNTXsWyOrOudbar6msd1llHiITPJ4kXPeDtiEEBRVUXp";


    public function setDevices($devices = [])
    {
        $this->devices = $devices;
    }

    public function setPayload(array $data)
    {
        $this->payload = $data;
    }

    public function prepareMessageContent($title, $body, $image = '')
    {
        $this->message = [
            'data' => [
                'title'        => $title,
                'body'         => $body,
                'sound'        => $this->sound,
                'extraPayload' => $this->payload,
            ]
        ];
    }


    public function sendPushMessage()
    {

        $pushAndroid = new PushNotification('fcm');
        $pushAndroid->setMessage($this->message);
        $pushAndroid->setConfig([
            "dry_run" => false
        ]);

        $pushAndroid->setApiKey($this->credentials);
        $pushAndroid->setDevicesToken($this->devices);
        $pushAndroid->send()->getFeedback();
//        dump($pushAndroid->send()->getFeedback());
//        $invalidTokens = $pushAndroid->getUnregisteredDeviceTokens();
//        $this->removeInvalidTokens($invalidTokens);
    }


    public function push($tokens)
    {

    }
}
