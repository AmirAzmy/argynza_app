<?php

namespace App\ThirdParty;

trait WebNotification
{
    private $credentials = "AAAAoEGDseg:APA91bHzxe0V65A2MFbalaEuRH-0_SWacv81IIOVzY6iBI3WO7d-_ajC_uJdBUdPfdkkio9EgOWbdNsOkyBqyB_sDezYyrFLFNTXsWyOrOudbar6msd1llHiITPJ4kXPeDtiEEBRVUXp";
    private $devices = [];
    private $payload = [];
    private $sound = "default";
    private $message = [];

    public function setDevices($devices = [])
    {
        $this->devices = $devices;
    }

    public function prepareMessageContent($title, $body, $image = '')
    {
        $this->message = [
            'icon'         => '',
            'image'        => $image,
            'title'        => $title,
            'body'         => $body,
            'sound'        => $this->sound,
            'extraPayload' => $this->payload
        ];

    }

    public function setPayload(array $data)
    {
        $this->payload = $data;
    }

    public function sendPushMessage()
    {
        if (count($this->devices) == 0) {
            return;
        }
        $devices = array_unique($this->devices);
        $header = [
            'Authorization: Key='.$this->credentials,
            'Content-Type: Application/json'
        ];
        $payload = [
            'registration_ids' => $devices,
            'data'             => $this->message
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => $header
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        dump($response);
        curl_close($curl);

    }

}
