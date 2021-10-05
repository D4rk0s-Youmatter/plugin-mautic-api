<?php

namespace D4rk0s\WpMauticApi\API;

use WP_REST_Request;
use WP_REST_Response;

abstract class AbstractRestApiRoutes
{
    public const API_NAMESPACE = "youmatter/v1";

    public static function init()
    {
      self::registerRestAPIRoutes();
    }

    protected static function registerRestAPIRoutes()
    {
      $endpoint = static::getEndpoint();
      $methods = static::getMethods();

      add_action('rest_api_init', function () use ($endpoint, $methods) {
          register_rest_route(self::API_NAMESPACE, '/'.$endpoint, array(
              'methods' => implode(",", $methods),
              'callback' => array(static::class, 'callback')
          ));
      });
    }

    abstract public static function callback(WP_REST_Request $request) : WP_REST_Response;
    abstract protected static function getEndpoint() : string;
    abstract protected static function getMethods() : array;
}
