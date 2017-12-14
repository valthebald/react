<?php

namespace Drupal\react\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

/**
 * Displays the React module settings page.
 */
class ReactAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['react.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('react.settings');

    $form['library'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('React Library'),
    ];

    if (function_exists('libraries_detect')) {
      $info = libraries_detect('react');
      if ($info['installed'] == FALSE) {
        drupal_set_message($this->t('React library not installed. Download it from <a href="@react-url" target="_blank">the official page</a>. This module expects the library to be at sites/all/libraries/react/build/react.js , along with other .js files.', array('@react-url' => 'https://facebook.github.io/react/')));
      }
      else {
        $form['library']['#description'] = $this->t('React library installed. <strong>Version detected: @version</strong>.<p>After changing these settings, flush caches.</p>', array('@version' => check_plain($info['version'])));
      }
    }

    $form['library']['react_addons'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('React with addons'),
      '#default_value' => $config->get('react_addons'),
      '#description' => $this->t('Load <strong>react-with-addons.min.js</strong> file instead of <strong>react.min.js</strong>. <a href="@react-url-addons" target="_blank">More info about addons.</a> Default: disabled.', array('@react-url-addons' => 'https://facebook.github.io/react/docs/addons.html')),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('react.settings');
    $react_addons = $form_state->getValue('react_addons');
    $config->set('react_addons', $react_addons);
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'react.admin_settings_form';
  }

}
