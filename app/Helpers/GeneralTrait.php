<?php

namespace App\Helpers;

use Carbon\Carbon;

trait GeneralTrait
{

    public function setDelayMethod()
    {
        $baseDelay = json_encode(now());

        $getDelay = cache('jobs.' . SendEmail::class, $baseDelay);

        $setDelay = Carbon::parse(
            json_decode($getDelay)
        )->addSeconds(10);

        cache([
            'jobs.' . SendEmail::class => json_encode($setDelay)
        ], 5);

        return $setDelay;
    }

    public function setLocalization()
    {
        $lang = ['en', 'ar'];
        // Check header request and determine localization
        if (request()->hasHeader('accept-language')) {
            $local = (in_array(request()->header('accept-language'), $lang)) ?
                request()->header('accept-language') : 'en';
        }
        else {
            $local = 'en';
        }
        // set laravel localization
        app()->setLocale($local);
    }

    public function randomPassword()
    {
        $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass        = array();               //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function unlink($data, $type)
    {
        $originalData = $data->getOriginal($type);
        $attributeData = $data->getAttribute($type);
        $temp = str_replace(url('uploads') . '/', '',$attributeData);
        if($originalData !== $temp) {
            $path = public_path() . '/uploads/' . $data;
            if (file_exists($path) && is_file($path)) {
                unlink($path);
            }
        }
    }
}
