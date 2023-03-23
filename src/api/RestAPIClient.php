<?php
namespace Pam\api;
class RestClient {
  private $url;
  private $headers;
  private $method;
  private $body;
  private $userAgent;
  private $referer;

  public function __construct($url, $headers = [], $method = 'GET', $body = '', $userAgent = '', $referer = '') {
    $this->url = $url;
    $this->headers = $headers;
    $this->method = $method;
    $this->body = $body;
    $this->userAgent = $userAgent;
    $this->referer = $referer;
  }

  public function setHeaders($headers) {
    $this->headers = $headers;
  }

  public function setMethod($method) {
    $this->method = $method;
  }

  public function setBody($body) {
    $this->body = $body;
  }

  public function sendRequest() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    curl_setopt($ch, CURLOPT_REFERER, $this->referer);
    $cookies = http_build_cookie($_COOKIE, '', ';');
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}