<?php

class CAPL_PluginController {

    public function __construct() {

        $class_name = get_class($this);
        register_activation_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_activation"));
        register_deactivation_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_deactivation"));
        register_uninstall_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_uninstall"));

        
          
        
        add_action("init", array(&$this, "init"));

        if ($this->is_enabled()):
            add_action('admin_footer-edit.php', array(&$this, "action_admin_footer"));
        endif;

        new CAPL_SettingsController();
    }

    public function init() {
        
        load_plugin_textdomain("colored-admin-post-list", true, CAPL_PLUGIN_RELATIVE_DIR . '/languages/');
        
        $onetime_upgrade = get_option("capl-one-time-upgrade");

        if ($onetime_upgrade === false):
            update_option(CAPL_Constants::OPTION_VERSION, CAPL_VERSION);
            update_option("capl-color-publish", get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PUBLISH, CAPL_Constants::DEFAULT_COLOR_PUBLISH));
            update_option("capl-color-draft", get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_DRAFTS, CAPL_Constants::DEFAULT_COLOR_DRAFTS));
            update_option("capl-color-pending", get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PENDING, CAPL_Constants::DEFAULT_COLOR_PENDING));
            update_option("capl-color-future", get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_FUTURE, CAPL_Constants::DEFAULT_COLOR_FUTURE));
            update_option("capl-color-private", get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PRIVATE, CAPL_Constants::DEFAULT_COLOR_PRIVATE));
            update_option("capl-one-time-upgrade", true);
        endif;
    }

    public static function on_activation() {

        if (!get_option(CAPL_Constants::OPTION_INSTALLED)) {
            update_option(CAPL_Constants::SETTING_ENABLED, "1");
            update_option(CAPL_Constants::OPTION_INSTALLED, "1");
            update_option(CAPL_Constants::OPTION_VERSION, CAPL_VERSION);

            if (false === get_option("capl-color-publish")):
                update_option("capl-color-publish", CAPL_Constants::DEFAULT_COLOR_PUBLISH);
            endif;

            if (false === get_option("capl-color-draft")):
                update_option("capl-color-draft", CAPL_Constants::DEFAULT_COLOR_DRAFTS);
            endif;

            if (false === get_option("capl-color-pending")):
                update_option("capl-color-pending", CAPL_Constants::DEFAULT_COLOR_PENDING);
            endif;

            if (false === get_option("capl-color-future")):
                update_option("capl-color-future", CAPL_Constants::DEFAULT_COLOR_FUTURE);
            endif;

            if (false === get_option("capl-color-private")):
                update_option("capl-color-private", CAPL_Constants::DEFAULT_COLOR_PRIVATE);
            endif;
        }
    }

    public static function on_deactivation() {

    }

    public static function on_uninstall() {
        delete_option(CAPL_Constants::OPTION_INSTALLED);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_DRAFTS);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_FUTURE);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PUBLISH);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PENDING);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PRIVATE);
        delete_option(CAPL_Constants::SETTING_ENABLED);
        delete_option("capl-one-time-upgrade");
    }

    public function action_admin_footer() {
        ?>
        <style type="text/css">
        <?php
        $default_post_statuses = CAPL_Helper::get_post_statuses_default();

        foreach ($default_post_statuses as $default_post_status):
            echo $this->style_builder("status-" . $default_post_status["name"], $default_post_status["option_handle"]);
        endforeach;

        $custom_post_statuses = CAPL_Helper::get_post_statuses_custom();

        foreach ($custom_post_statuses as $custom_post_status):
            echo $this->style_builder("status-" . $custom_post_status["name"], $custom_post_status["option_handle"]);
        endforeach;
        ?>
        </style>
        <?php
    }

    private function style_builder($css_class, $option, $important = true) {
        $option = get_option($option);

        if ($option === false || empty($option)):
            return "";
        endif;

        $background_color = $option;
        $style = "";
        $style = "." . $css_class . "{ background: " . $background_color . $style .= (($important == true) ? " !important" : "") . "; }\r\n";
        return $style;
    }

    private function is_enabled() {
        $setting = get_option(CAPL_Constants::SETTING_ENABLED);
        return $setting === "1" ? true : false;
    }

}
?>
