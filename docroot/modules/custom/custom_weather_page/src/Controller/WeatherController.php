<?php

namespace Drupal\custom_weather_page\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\custom_weather_page\WeatherServiceClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Weather page controller.
 */
class WeatherController extends ControllerBase {

  /**
   * @var \Drupal\custom_weather_page\WeatherServiceClient
   */
  protected $weatherService;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Creates a Weather page Controller.
   *
   * @param \Drupal\custom_weather_page\WeatherServiceClient
   *   The weather service.
   * @param \Drupal\custom_weather_page\WeatherServiceClient
   *   The weather service.
   */
  public function __construct(WeatherServiceClient $weather_service, ConfigFactoryInterface $config_factory) {
    $this->weatherService = $weather_service;
    $this->configFactory = $config_factory->get('weather.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('custom_weather_page_sevice'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function content($city_name = '', $country_code = '') {
    $content = [];
    $city_name = $city_name ?: $this->configFactory->get('city_name');
    if ($weather = $this->weatherService->weather($city_name, $country_code)) {
      $content = [
        'city_name' => $city_name,
        'current_weather' => $weather['weather'][0]['main'],
        'description' => $weather['weather'][0]['description'],
        'temperature' => $weather['main']['temp'],
        'max_temperature' => $weather['main']['temp_max'],
        'min_temperature' => $weather['main']['temp_min'],
        'humidity'  => $weather['main']['humidity'],
        'pressure'  => $weather['main']['pressure'],
      ];
    }

    return [
      '#theme' => 'custom__weather_page',
      '#content' => $content
    ];
  }

}
