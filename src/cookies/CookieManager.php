<?php
namespace Pam\cookies;


class CookieManager {
  
  private $cookiePath = '/';
  private $cookieDomain = '';
  private $cookieSecure = false;
  private $cookieHttpOnly = false;
  private $cookieSameSite = 'Lax';

  public function __construct($cookieDomain, $cookieSecure = false, $cookieHttpOnly = false, $cookieSameSite = 'Lax', $cookiePath = '/') {
    $this->cookieDomain = $cookieDomain;
    $this->cookieSecure = $cookieSecure;
    $this->cookieHttpOnly = $cookieHttpOnly;
    $this->cookieSameSite = $cookieSameSite;
    $this->cookiePath = $cookiePath;
  }

  public function setCookie($name, $value, $days = 1825) {
    if ($this->cookieSameSite !== '') {
      setcookie($name, $value, [
        'expires' => time() + ($days * 86400000),
        'path' => $this->cookiePath,
        'domain' => $this->cookieDomain,
        'secure' => $this->cookieSecure,
        'httponly' => $this->cookieHttpOnly,
        'samesite' => $this->cookieSameSite
      ]);
    } else {
      setcookie($name, $value, time() + ($days * 86400000), $this->cookiePath, $this->cookieDomain, $this->cookieSecure, $this->cookieHttpOnly);
    }
  }

  public function getCookie($name) {
    return $_COOKIE[$name] ?? null;
  }

  public function deleteCookie($name) {
    if (isset($_COOKIE[$name])) {
      unset($_COOKIE[$name]);
      if ($this->cookieSameSite !== '') {
        setcookie($name, '', [
          'expires' => time() - 3600,
          'path' => $this->cookiePath,
          'domain' => $this->cookieDomain,
          'secure' => $this->cookieSecure,
          'httponly' => $this->cookieHttpOnly,
          'samesite' => $this->cookieSameSite
        ]);
      } else {
        setcookie($name, '', time()-3600, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, $this->cookieHttpOnly);
      }
    }
  }
}
