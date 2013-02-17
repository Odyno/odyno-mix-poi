<?php
/*
Plugin Name: Odyno MixPOI
Plugin URI:  http://www.mixare.org/wordpress-plugin/
Description: Wordpress plugin to easily create mixare data sources. Mixare is an open source augmented reality browser for android and iOs. Both the plugin and mixare are licensed under the GPLv3.
Author: Alessandro Staniscia
Version: 0.0.1-snapshot
Author URI: http://www.mixare.org/
*/

//add definition of constant
define('ODYNOMIXPOI_VERSION', '0.0.1-SNAPSHOT');
define('ODYNOMIXPOI__FILE__', ABSPATH . PLUGINDIR . '/odyno-mixpoi/odyno-mixpoi.php');
define('ODYNOMIXPOI_DIR', ABSPATH . PLUGINDIR . '/odyno-mixpoi/');
define('ODYNOMIXPOI_URL', plugin_dir_url(ODYNOMIXPOI__FILE__).'/odyno-mixpoi/');



//installing the necessary options -> install/installer.php
require_once(ODYNOMIXPOI_DIR.'/install/installer.php');

require_once(ODYNOMIXPOI_DIR.'/omp-menu-manager.php');

?>