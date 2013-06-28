<?php
/*
  Plugin Name: Colored Admin Post List
  Plugin URI: http://wordpress.org/plugins/colored-admin-post-list/
  Description: Highlights the background of draft, pending, future, private and published posts in the wordpress admin
  Author: Stevie
  Version: 1.0
  Author URI: http://www.eracer.de
 */

define("CAPL_PLUGIN", plugin_basename(__FILE__));
define("CAPL_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("CAPL_PLUGIN_URL", plugin_dir_url(__FILE__));
define("CAPL_PLUGIN_RELATIVE_DIR", dirname(plugin_basename(__FILE__)));
define("CAPL_PLUGIN_FILE", __FILE__);


require_once(CAPL_PLUGIN_DIR . "classes/class-constants.php");
require_once(CAPL_PLUGIN_DIR . "classes/class-helper.php");

require_once(CAPL_PLUGIN_DIR . "controller/class-plugin-controller.php");
require_once(CAPL_PLUGIN_DIR . "controller/class-settings-controller.php");

// start the engine!
new CAPL_PluginController();
?>