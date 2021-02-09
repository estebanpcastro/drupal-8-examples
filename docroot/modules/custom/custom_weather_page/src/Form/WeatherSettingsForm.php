<?php

namespace Drupal\custom_weather_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class WeatherSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'custom_weather_page_settings_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('weather.settings');
    $form['weather'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Basic configuration'),
    ];
    $form['weather']['city_name'] = [
      '#title' => $this->t('City name of default service consuming'),
      '#type' => 'textfield',
      '#require' => TRUE,
      '#default_value' => $config->get('city_name'),
    ];
    $form['weather']['country_code'] = [
      '#title' => $this->t('Country code of default service consuming'),
      '#type' => 'textfield',
      '#default_value' => $config->get('country_code'),
    ];
    $form['weather']['api_endpoint'] = [
      '#title' => $this->t('Api endpoint of access to service'),
      '#type' => 'textfield',
      '#require' => TRUE,
      '#default_value' => $config->get('api_endpoint'),
    ];
    $form['weather']['api_key'] = [
      '#title' => $this->t('Api key of access to service'),
      '#type' => 'textfield',
      '#require' => TRUE,
      '#default_value' => $config->get('api_key'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    return parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('weather.settings');
    $config->set('city_name', $form_state->getValue('city_name'));
    $config->set('country_code', $form_state->getValue('country_code'));
    $config->set('api_endpoint', $form_state->getValue('api_endpoint'));
    $config->set('api_key', $form_state->getValue('api_key'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  protected function getEditableConfigNames() {
    return [
      'weather.settings',
    ];
  }
}
