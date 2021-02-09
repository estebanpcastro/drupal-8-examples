<?php

namespace Drupal\custom_weather_page;


use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Http\ClientFactory;

class WeatherServiceClient {

  /**
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * WeatherServiceClient constructor.
   *
   * @param $http_client_factory \ClientFactory
   * @param $config_factory \Drupal\Core\Config\ConfigFactoryInterface
   */
  public function __construct(ClientFactory $http_client_factory, ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory->get('weather.settings');
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => $this->configFactory->get('api_endpoint'),
    ]);
  }

  /**
   * Get current weather.
   *
   * @param string $city_name
   * @param string $country_code
   * @return mixed array or boolean with response of service.
   */
  public function weather($city_name = '', $country_code = '') {
    $country_code = $country_code ? ',' . $country_code : '';
    $country_code = !$country_code && $this->configFactory->get('country_code') ? ',' . $this->configFactory->get('country_code') : '';
    try {
      $response = $this->client->get('weather', [
        'query' => [
          'q' => $city_name . $country_code,
          'appid' => $this->configFactory->get('api_key')
        ]
      ]);
      return Json::decode($response->getBody());
    }
    catch(Exception $e) {
      echo "Error: " . $e->getMessage();
    }

    return FALSE;
  }

}
