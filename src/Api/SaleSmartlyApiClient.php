<?php

class SaleSmartlyApiClient
{
    public static function curl($apiUrl, $requestType= 'GET', $postData=[], $headerData=[]){
        $curl = curl_init($apiUrl);

        if($requestType == 'GET'){
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        }elseif ($requestType == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }elseif($requestType == 'POST'){
            if (isset($postData['filename'])) {
                $postData['filename'] = new \CURLFile($postData['filename']);
            }
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif($requestType == 'PUT' ){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        }

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // header处理
        if (count($headerData) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerData);
        }

        $result = curl_exec($curl);

        if ($result === false) {
            $extra = [
                'curl_error' => curl_error($curl),
                'curl_errno' => curl_errno($curl)
            ];
        } else {
            $extra = json_decode($result, true);
        }
        curl_close($curl);
        return $extra;
    }
    
}