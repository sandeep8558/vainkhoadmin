<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Device;

class NotificationController extends Controller
{

    public function sendNotification($title, $msg){
        $data = [];
        $data["msg"] = $msg;
        $data["title"] = $title;

        Device::chunk(50, function($devices) use($data){
            $tokens = [];
            foreach($devices as $device){
                $tokens[] = $device->token;
            }
            echo $response = $this->sendFirebasePush($tokens, $data);
        });

        
        //$tokens[] = "dTpvN6doiUJKqiqzcJChsj:APA91bESmMgNkdBQsv3M4dTAl1AOLZeSr0mF6Bs1QlYbRgNuVXLLQ4_kQfSegU-DBswjthWKYFSsULLXhR1pNWKVMFEWy-qEgwf7dRQqW4IccCQKyzRsC4ILnr-Ulax0qt2rz8YpVMz5";
        //$tokens[] = "dOBV1iecvULXoA0hl7_zkZ:APA91bGot6w7qx-FEyZ97zGFi471ydPCKOU2COancQtEbaldAwoo5gCUPdHbPx4gviQJsiq0ZkLo3I5EjKayzMKlzjBKV5i0TtcDVj78nngFE5U4u_XLABfygnYosgqYPP2LvGPHWHZT";
        //$tokens[] = "dWRW7-5iDU-mpmULdG5aAn:APA91bHZJzpdb9shoGkP1FAP6D5PT5tIXfH6W--HJSOmYF_FdT4kFXpk4yW-7HIrzSjN-GBPfgCY6D47xzyEdouBIKAI-6X6-WcUGl-jd6Ja7NAFcEwUWvtwZ8daU7Cdrdvs681pQeeR";

        //return $response = $this->sendFirebasePush($tokens, $data);
    }

    public function sendFirebasePush($tokens, $data){
        $serverKey = "AAAAjhL_T3s:APA91bFbiqO9mKbgPoUfKj4tw-kfnmF5h-qtcxoIlAKnof-t8DdaTTR4h_YVq7hDr0YBrG2SCXhC959W4F-C00FMXnmBoM_ChWSvtEr1zlJthjaEDtKv1tmmlwPNfDumQh4P6fqpNpuL";
        $msg = array(
            'msg' => $data['msg'],
            'title' => $data['title']
        );
        $notifyData = [
            "body" => $data["msg"],
            "title" => $data['title']
        ];

        $registrationIds = $tokens;

        if(count($tokens) > 1){
            $fields = array (
                "registration_ids" => $registrationIds,
                "notification" => $notifyData,
                "data" => $msg,
                "priority" => "high"
            );
        } else {
            $fields = array (
                "to" => $registrationIds[0],
                "notification" => $notifyData,
                "data" => $msg,
                "priority" => "high"
            );
        }

        $headers[] = "Content-Type: application/json";
        $headers[] = "Authorization: key=" . $serverKey;

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, "https://fcm.googleapis.com/fcm/send" );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
        $result = curl_exec($ch);
        if($result === FALSE){
            die("FCM Send Error: " . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

}
