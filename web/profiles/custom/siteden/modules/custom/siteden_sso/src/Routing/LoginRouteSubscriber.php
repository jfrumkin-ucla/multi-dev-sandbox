<?php

namespace Drupal\siteden_sso\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Drupal\simplesamlphp_auth\Service\SimplesamlphpAuthManager;

/**
 * Listens to the dynamic route events.
 */
class LoginRouteSubscriber extends RouteSubscriberBase {

  /**
   * The SimpleSAML Authentication helper service.
   *
   * @var \Drupal\simplesamlphp_auth\Service\SimplesamlphpAuthManager
   */
  protected $simplesaml;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\simplesamlphp_auth\Service\SimplesamlphpAuthManager $simplesaml
   *   The SimpleSAML Authentication helper service.
   */
  public function __construct(SimplesamlphpAuthManager $simplesaml) {
    $this->simplesaml = $simplesaml;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change path '/user/login' to '/saml_login' if simplesaml is activated.
    if ($this->simplesaml->isActivated() && $route = $collection->get('user.login')) {
      $route->setPath('/saml_login');
    }
  }

}
