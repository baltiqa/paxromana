<?php
namespace App\Monero;

/**
 * Created by PhpStorm.
 * User: faree
 * Date: 1/19/2020
 * Time: 4:21 PM
 */
class Monero
{

    private $username;
    private $password;
    private $proto;
    private $host;
    private $port;
    private $url;

    public $status;
    public $error;
    public $raw_response;
    public $response;
    private $id = 0;


    public function __construct($username, $password, $host = 'localhost', $port = 18083, $url = "json_rpc")
    {
        $this->username      = $username;
        $this->password      = $password;
        $this->host          = $host;
        $this->port          = $port;
        $this->url           = $url;
        $this->proto         = 'http';
    }

    public function __call($method, $params)
    {
        $this->status       = null;
        $this->error        = null;
        $this->raw_response = null;
        $this->response     = null;

        $params = array_values($params);

        $this->id++;

        if(count($params) > 0){
            $params = $params[0];
        }

        $request = json_encode(array(
            'method' => $method,
            'params' => $params,
            'id'     => $this->id
        ));

        $port = $this->port;

        if($method == "get_fee_estimate"){
            $port = $port - 2; // switch to daemon port
        }

        $curl    = curl_init("{$this->proto}://{$this->host}:{$port}/{$this->url}");
        $options = array(
            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
            CURLOPT_USERPWD        => $this->username . ':' . $this->password,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_HTTPHEADER     => array('Content-type: application/json'),
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $request
        );

        if (ini_get('open_basedir')) {
            unset($options[CURLOPT_FOLLOWLOCATION]);
        }

        curl_setopt_array($curl, $options);

        $this->raw_response = curl_exec($curl);

        $this->response     = (object) json_decode($this->raw_response, true);

        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $curl_error = curl_error($curl);
        curl_close($curl);
        if (!empty($curl_error)) {
            $this->error = $curl_error;
        }
        if (isset($this->response->error)){

            $this->response = $this->response->error['message'];
        } elseif ($this->status != 200) {

            switch ($this->status) {
                case 400:
                    $this->response = (object) json_encode(array('error' => 'HTTP_BAD_REQUEST'));
                    break;
                case 401:
                    $this->response = (object) json_encode(array('error' => 'HTTP_UNAUTHORIZED'));
                    break;
                case 403:
                    $this->response = (object) json_encode(array('error' => 'HTTP_FORBIDDEN'));
                    break;
                case 404:
                    $this->response = (object) json_encode(array('error' => 'HTTP_NOT_FOUND'));
                    break;
            }
        }

        return $this->response;
    }

}
