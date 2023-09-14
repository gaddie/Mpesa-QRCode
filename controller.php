<?php

class MpesaController
{
    public function dynamicQR()
    {
        $cpi = '174379';
        $request_data = [
            "MerchantName" => "testing",
            "RefNo" => "Invoice Test",
            "Amount" => 1,
            "TrxCode" => "PB",
            "CPI" => $cpi
        ];

        $url = 'https://sandbox.safaricom.co.ke/mpesa/qrcode/v1/generate';
        $data_string = json_encode($request_data);

        // Set up cURL options
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $this->newAccessToken()));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        // Execute the cURL request
        $curl_response = curl_exec($curl);

        // Handle the response
        if ($curl_response === false) {
            die('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        // Decode the API response
        $data = json_decode($curl_response);
   
        $qrCodeData = $data->QRCode;
        
        // Display the QR code (you can customize this part as needed)
        echo '<img src="data:image/png;base64,' . $qrCodeData . '" alt="M-Pesa QR Code">';
    }

    public function newAccessToken()
    {
        $consumer_key = "fksxuEFE8z1g2LlE9GuUhvjF4tkY0AQE";
        $consumer_secret = "SejKXeZevgkj5Ucb";
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        // Set up cURL options
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials, "Content-Type: application/json"));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $curl_response = curl_exec($curl);

        // Handle the response
        if ($curl_response === false) {
            die('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        // Decode the API response
        $access_token = json_decode($curl_response);
        
        // Extract and return the access token
        return $access_token->access_token;
    }
}

// Usage
$controller = new MpesaController();
$controller->dynamicQR();
