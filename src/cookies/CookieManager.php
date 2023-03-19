<?php
namespace Pam\cookies;


class CookieManager {
  
  private $cookiePath = '/';
  private $cookieDomain = '';
  private $cookieSecure = false;
  private $cookieHttpOnly = true;
  private $cookieSameSite = 'Lax';

  public function __construct($cookieDomain, $cookieSecure = false, $cookieHttpOnly = true, $cookieSameSite = 'Lax', $cookiePath = '/') {
    $this->cookieDomain = $cookieDomain;
    $this->cookieSecure = $cookieSecure;
    $this->cookieHttpOnly = $cookieHttpOnly;
    $this->cookieSameSite = $cookieSameSite;
    $this->cookiePath = $cookiePath;
  }

  public function setCookie($name, $value, $expire = 0) {
    setcookie($name, $value, $expire, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, $this->cookieHttpOnly);
    if ($this->cookieSameSite !== '') {
      setcookie($name, $value, [
        'expires' => $expire,
        'path' => $this->cookiePath,
        'domain' => $this->cookieDomain,
        'secure' => $this->cookieSecure,
        'httponly' => $this->cookieHttpOnly,
        'samesite' => $this->cookieSameSite
      ]);
    }
    // if ($this->cookieSameSite !== '') {
    //   setcookie($name.'_sameSite', $this->'None', $expire, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, true);
    // }
  }

  public function getCookie($name) {
    return $_COOKIE[$name] ?? null;
  }

  public function deleteCookie($name) {
    setcookie($name, '', time()-3600, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, $this->cookieHttpOnly);
    setcookie($name.'_sameSite', '', time()-3600, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, true);
  }

}
