<?php

namespace D4rk0s\WpMauticApi\Model;

class OAuth2TokenModel
{
  private string $accessToken;
  private int $expire;
  private string $tokenType;
  private string $refreshToken;

  public function __construct(string $accessToken, int $expire, string $tokenType, string $refreshToken)
  {
    $this->accessToken = $accessToken;
    $this->expire = $expire;
    $this->tokenType = $tokenType;
    $this->refreshToken = $refreshToken;
  }

  public function getAccessToken(): string
  {
    return $this->accessToken;
  }

  public function getExpire(): int
  {
    return $this->expire;
  }

  public function getTokenType(): string
  {
    return $this->tokenType;
  }

  public function getRefreshToken(): string
  {
    return $this->refreshToken;
  }

  public function __serialize() : array
  {
    return [
      'accessToken' => $this->accessToken,
      'expire' => $this->expire,
      'tokenType' => $this->tokenType,
      'refreshToken' => $this->refreshToken
    ];
  }
}