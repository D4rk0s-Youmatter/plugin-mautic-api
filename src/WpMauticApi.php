<?php

namespace D4rk0s\WpMauticApi;

use D4rk0s\WpMauticApi\API\MauticAuth;
use Mautic\Api\Api;
use Mautic\MauticApi;

class WpMauticApi
{
  protected static ?MauticApi $mauticApi = null;
  protected static array $mauticApiContexts = [];

  public static function init()
  {
    session_start();
    MauticAuth::init();

    add_action( 'admin_menu', function() {
      add_menu_page(
        __('Mautic api page', 'mautic-api'),
        __('Mautic api', 'mautic-api'),
        'manage_options',
        'mautic-api-page',
        function() {
          require_once __DIR__.'/../partial/pageContent.php';
        }
      );
    });
  }

  public static function plugin_deactivation() : void
  {
    delete_option(MauticAuth::MAUTIC_OAUTH_OPTION);
    delete_option(MauticAuth::MAUTIC_API_PRIVATE_KEY);
    delete_option(MauticAuth::MAUTIC_API_PUBLIC_KEY);
    delete_option(MauticAuth::MAUTIC_BASEURL);
    delete_option(MauticAuth::SECURITY_HASH);
  }

  public static function getAPI(string $context) : Api
  {
    if(self::$mauticApi === null) {
      self::$mauticApi = new MauticApi();
    }

    if(!array_key_exists($context, self::$mauticApiContexts)) {
      self::$mauticApiContexts[$context] = self::$mauticApi->newApi($context, MauticAuth::getAuth(), MauticAuth::MAUTIC_BASEURL."/api");
    }

    return self::$mauticApiContexts[$context];
  }
}