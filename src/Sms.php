<?php

namespace Trez\RayganSms;

use GuzzleHttp\Client as HttpClient;

class Sms
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $url_send_message;

    /** @var string */
    protected $url_send_auto_auth_code;

    /** @var string */
    protected $url_check_auth_code;

    /** @var string */
    protected $url_send_auth_code;

    /** @var string */
    protected $user_name;

    /** @var string */
    protected $password;

    /** @var string */
    protected $phone_number;

    /**
     * Sms constructor.
     *
     * @param string $user_name
     * @param string $password
     * @param string $phone_number
     */
    public function __construct($user_name, $password, $phone_number)
    {
        $this->user_name = $user_name;
        $this->password = $password;
        $this->phone_number = $phone_number;
        $this->url_send_message = 'https://RayganSMS.com/SendMessageWithPost.ashx';
        $this->url_send_auto_auth_code = 'https://smspanel.Trez.ir/AutoSendCode.ashx';
        $this->url_check_auth_code = 'https://smspanel.Trez.ir/CheckSendCode.ashx';
        $this->url_send_auth_code = 'https://smspanel.Trez.ir/SendMessageWithCode.ashx';

        $this->client = new HttpClient([
            'timeout'         => 10,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * @param string $reciver_number
     * @param string $text_message
     *
     * @return string
     */
    public function sendMessage($reciver_number, $text_message)
    {
        $params = [
            'UserName'    => $this->user_name,
            'Password'    => $this->password,
            'PhoneNumber' => $this->phone_number,
            'Smsclass'    => '1',
            'RecNumber'   => $reciver_number,
            'MessageBody' => $text_message,
        ];

        $response = $this->client->request('POST', $this->url_send_message, ['form_params' => $params]);
        $response = \json_decode((string) $response->getBody(), true);

        return $response;
    }

    /**
     * @param $reciver_number
     * @param null $text_message
     * @param bool $autoGenerateCode
     *
     * @return string
     */
    public function sendAuthCode($reciver_number, $text_message = null, $autoGenerateCode = true)
    {
        if ($autoGenerateCode) {
            $params = [
                'UserName' => $this->user_name,
                'Password' => $this->password,
                'Mobile'   => $reciver_number,
                'Footer'   => $text_message,
            ];
            $response = $this->client->request('POST', $this->url_send_auto_auth_code, ['form_params' => $params]);
        } else {
            $params = [
                'UserName' => $this->user_name,
                'Password' => $this->password,
                'Mobile'   => $reciver_number,
                'Message'  => $text_message,
            ];

            $response = $this->client->request('GET', $this->url_send_auth_code, ['query' => $params]);
        }

        $response = \json_decode((string) $response->getBody(), true);

        return $response;
    }

    /**
     * @param string $reciver_number
     * @param string $reciver_code
     *
     * @return string
     */
    public function checkAuthCode($reciver_number, $reciver_code)
    {
        $params = [
            'UserName' => $this->user_name,
            'Password' => $this->password,
            'Mobile'   => $reciver_number,
            'Code'     => $reciver_code,
        ];

        $response = $this->client->request('POST', $this->url_check_auth_code, ['form_params' => $params]);
        $response = \json_decode((string) $response->getBody(), true);

        return $response;
    }
}
