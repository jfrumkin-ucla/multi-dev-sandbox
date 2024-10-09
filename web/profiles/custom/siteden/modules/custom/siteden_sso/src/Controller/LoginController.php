<?php

namespace Drupal\siteden_sso\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This controller is used to provide an alternative backdoor login form.
 *
 * Returns drupal default user login form.
 */
class LoginController extends ControllerBase {

  /**
   * Return user Login Form.
   *
   * @return \Drupal\user\Form\UserLoginForm form.
   */
  public function backdoorLogin() {
    $form_builder = $this->formBuilder();
    return $form_builder->getForm("Drupal\user\Form\UserLoginForm");
  }

}
