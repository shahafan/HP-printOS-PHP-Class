<?php
/**
* printos class by Shahaf
*/
class PrintOs
{
  private $token;
  private $secret;
  private $baseUrl;
  private $path;
  private $time;
  private $httpHeaderString;

  public function __construct($data)
  {
    $this->token = $data['token'];
    $this->secret = $data['secret'];
    $this->baseUrl = $data['baseUrl'];
    $this->time = $this->zTimeStamp();
  }

  public function getToken()
  {
    return $this->token;
  }

  public function getSecret()
  {
    return $this->secret;
  }

  public function getBaseUrl()
  {
    return $this->baseUrl;
  }

  public function getPath()
  {
    return $this->path;
  }

  public function setToken($token)
  {
    $this->token = $token;
  }

  public function setSecret($secret)
  {
    $this->secret = $secret;
  }

  public function setBaseUrl($baseUrl)
  {
    $this->baseUrl = $baseUrl;
  }
  public function setPath($path)
  {
    $this->path = $path;
  }


  public function generateHttpHeaderString($method)
  {
  	$str = $method . ' ' . $this->path . $this->time;
  	$hmacHash = hash_hmac('sha1', $str, $this->secret);
  	$this->httpHeaderString = $this->token . ':' . $hmacHash;
  }

  public function request($data)
  {
    $postData = json_encode($data);

    $headers = array(
    	"Content-type: application/json",
    	"x-hp-hmac-authentication: ".$this->httpHeaderString,
    	"x-hp-hmac-date: ".$this->time,
    	"x-hp-hmac-algorithm: SHA1"
    );

    // Setup cURL
    $ch = curl_init($this->baseUrl.$this->path);

    curl_setopt_array($ch, array(
    	CURLOPT_POST => true,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_HTTPHEADER => $headers,
    	CURLOPT_POSTFIELDS => $postData
    ));

    // Send the request
    $response = curl_exec($ch);

    // Check for errors
    if($response === FALSE){
    	die(curl_error($ch));
    }

    return $response;

  }


  private function zTimeStamp()
  {
    $t = microtime(true);
  	$micro = sprintf("%03d",($t - floor($t)) * 1000);
  	return gmdate('Y-m-d\TH:i:s.', $t).$micro.'Z';
  }


}
