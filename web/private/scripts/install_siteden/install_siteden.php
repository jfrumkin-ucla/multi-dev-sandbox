<?php
/**
 * This script installs SiteDen after product_deploy hook. It also enables and/or
 * disables modules and sets default configuration for SiteDen sites on Pantheon.
 *
 * TODO: Once sites are all migrated to Pantheon, refactor the siteden profile to match these preferences.
 */

if (isset($_POST['environment']) && !in_array($_POST['environment'], array('test', 'live'))) {
  echo "Installing SiteDen...";
  passthru('drush si siteden -y');
  echo "Uninstalling Domain 301 Redirect module...";
  passthru('drush pm:uninstall domain_301_redirect -y');
  echo "Enabling Dblog...";
  passthru('drush pm:enable dblog -y');
  echo "Enabling Pantheon Advanced Page Cache...";
  passthru('drush pm:enable pantheon_advanced_page_cache -y');
  echo "Clearing cache...";
  passthru('drush cr -y');
}
