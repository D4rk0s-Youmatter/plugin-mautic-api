<?php

namespace D4rk0s\WpMauticApi\API;

use D4rk0s\WpMauticApi\Exception\InvalidNonceException;
use D4rk0s\WpMauticApi\Exception\NotInitializedException;
use D4rk0s\WpMauticApi\Model\OAuth2TokenModel;
use Mautic\Auth\ApiAuth;
use Mautic\Auth\AuthInterface;
use WP_REST_Request;
use WP_REST_Response;

class MauticAuth extends AbstractRestApiRoutes
{
  public const MAUTIC_OAUTH_OPTION = 'wp_mautic_session_oauth';
  public const MAUTIC_API_PRIVATE_KEY = 'wp_mautic_api_private_key';
  public const MAUTIC_API_PUBLIC_KEY = 'wp_mautic_api_public_key';
  public const MAUTIC_BASEURL = 'wp_mautic_base_url';
  public const ENDPOINT = 'mautic_callback';
  public const SECURITY_HASH = 'wp_mautic_security_hash';

   public static function callback(WP_REST_Request $request) : WP_REST_Response
   {
     if($_POST[self::MAUTIC_API_PRIVATE_KEY] && $_POST[self::MAUTIC_API_PUBLIC_KEY] && $_POST[self::MAUTIC_BASEURL] ) {
      if($_REQUEST['security_key'] !== get_option(self::SECURITY_HASH)) {
        throw new InvalidNonceException();
      }
      delete_option(self::SECURITY_HASH);
      update_option(self::MAUTIC_API_PRIVATE_KEY,$_POST[self::MAUTIC_API_PRIVATE_KEY]);
      update_option(self::MAUTIC_API_PUBLIC_KEY,$_POST[self::MAUTIC_API_PUBLIC_KEY]);
      update_option(self::MAUTIC_BASEURL,$_POST[self::MAUTIC_BASEURL]);
     }

     self::getAuth(true);

     wp_redirect(get_site_url().'/wp-admin/admin.php?page=mautic-api-page');
     exit;
   }

   public static function getAuth(bool $internalProcess = false) : AuthInterface
   {
     if(!$internalProcess && !get_option(self::MAUTIC_OAUTH_OPTION)) {
       throw new NotInitializedException(get_site_url().'/wp-admin/admin.php?page=mautic-api-page');
     }

     $apiAuth = new ApiAuth();

     $auth = $apiAuth->newAuth(self::getSettings());
     if($auth->validateAccessToken() && $auth->accessTokenUpdated()) {
       $data = $auth->getAccessTokenData();
       $OAuth2Token = new OAuth2TokenModel(
         $data['access_token'],
         (int) $data['expires'],
         $data['token_type'],
         $data['refresh_token']
       );
       update_option(self::MAUTIC_OAUTH_OPTION, serialize($OAuth2Token));
     }

     return $auth;
   }

   protected static function getSettings() : array
   {
     $callback = get_site_url().'/wp-json/'.self::API_NAMESPACE.'/'.self::getEndpoint();

     $settings = [
       'baseUrl'          => get_option(self::MAUTIC_BASEURL),       // Base URL of the Mautic instance
       'version'          => 'OAuth2', // Version of the OAuth can be OAuth2 or OAuth1a. OAuth2 is the default value.
       'clientKey'        => get_option(self::MAUTIC_API_PUBLIC_KEY),       // Client/Consumer key from Mautic
       'clientSecret'     => get_option(self::MAUTIC_API_PRIVATE_KEY),       // Client/Consumer secret key from Mautic
       'callback'         => $callback,       // Redirect URI/Callback URI for this script
     ];

     if(false !== $token = get_option(self::MAUTIC_OAUTH_OPTION))
     {
       /** @var OAuth2TokenModel $tokenDecoded */
       $tokenDecoded = unserialize($token, ['allowed_classes' => [OAuth2TokenModel::class]]);
       $settings['accessToken'] = $tokenDecoded->getAccessToken();
       $settings['accessTokenExpires'] = $tokenDecoded->getExpire();
       $settings['refreshToken'] = $tokenDecoded->getRefreshToken();
     }

     return $settings;
   }

   public static function getSecurityHash() : string
   {
     return md5(time().random_int(1,2888888));
   }

   protected static function getEndpoint() : string
   {
     return self::ENDPOINT;
   }

   protected static function getMethods() : array
   {
     return ["GET","POST"];
   }
}