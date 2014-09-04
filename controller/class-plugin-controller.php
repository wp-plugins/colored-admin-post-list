<?php

class CAPL_PluginController {

    public $settings_controller;

    public function __construct() {
        $this->settings_controller = new CAPL_SettingsController();

        //new CAPL_DashboardWidgetController();

        if ($this->is_enabled()) {
            add_action('admin_footer-edit.php', array(&$this, "action_admin_footer"));
        }

        $class_name = get_class($this);
        register_activation_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_activation"));
        register_deactivation_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_deactivation"));
        register_uninstall_hook(CAPL_PLUGIN_FILE, array("$class_name", "on_uninstall"));
    }

    private function is_enabled() {
        $setting = get_option(CAPL_Constants::SETTING_ENABLED);
        return $setting === "1" ? true : false;
    }

    public static function on_activation() {

        if (!get_option(CAPL_Constants::OPTION_INSTALLED)) {
            update_option(CAPL_Constants::SETTING_ENABLED, "1");
            update_option(CAPL_Constants::OPTION_INSTALLED, "1");
        }

        if (false == get_option(CAPL_Constants::SETTING_COLOR_DRAFTS)):
            update_option(CAPL_Constants::SETTING_COLOR_DRAFTS, CAPL_Constants::DEFAULT_COLOR_DRAFTS);
        endif;

        if (false == get_option(CAPL_Constants::SETTING_COLOR_FUTURE)):
            update_option(CAPL_Constants::SETTING_COLOR_FUTURE, CAPL_Constants::DEFAULT_COLOR_FUTURE);
        endif;

        if (false == get_option(CAPL_Constants::SETTING_COLOR_PUBLISH)):
            update_option(CAPL_Constants::SETTING_COLOR_PUBLISH, CAPL_Constants::DEFAULT_COLOR_PUBLISH);
        endif;

        if (false == (CAPL_Constants::SETTING_COLOR_PENDING)):
            update_option(CAPL_Constants::SETTING_COLOR_PENDING, CAPL_Constants::DEFAULT_COLOR_PENDING);
        endif;

        if (false == get_option(CAPL_Constants::SETTING_COLOR_PRIVATE)):
            update_option(CAPL_Constants::SETTING_COLOR_PRIVATE, CAPL_Constants::DEFAULT_COLOR_PRIVATE);
        endif;
    }

    public static function on_deactivation() {

    }

    public static function on_uninstall() {
        delete_option(CAPL_Constants::OPTION_INSTALLED);
        delete_option(CAPL_Constants::SETTING_COLOR_DRAFTS);
        delete_option(CAPL_Constants::SETTING_COLOR_FUTURE);
        delete_option(CAPL_Constants::SETTING_COLOR_PUBLISH);
        delete_option(CAPL_Constants::SETTING_COLOR_PENDING);
        delete_option(CAPL_Constants::SETTING_COLOR_PRIVATE);
        delete_option(CAPL_Constants::SETTING_ENABLED);
    }

    public function action_admin_footer() {
        ?>
        <style>
        <?php
        echo $this->style_builder("status-publish", CAPL_Constants::SETTING_COLOR_PUBLISH);
        echo $this->style_builder("status-draft", CAPL_Constants::SETTING_COLOR_DRAFTS);
        echo $this->style_builder("status-pending", CAPL_Constants::SETTING_COLOR_PENDING);
        echo $this->style_builder("status-future", CAPL_Constants::SETTING_COLOR_FUTURE);
        echo $this->style_builder("status-private", CAPL_Constants::SETTING_COLOR_PRIVATE);
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

}
?>
