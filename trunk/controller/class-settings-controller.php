<?php

class CAPL_SettingsController {

    public function __construct() {
        add_action("admin_init", array(&$this, "register_settings"));
        add_action("admin_menu", array(&$this, 'action_admin_menu'));
        add_action('admin_print_scripts-settings_page_' . CAPL_Constants::ADMIN_PAGE_OPTIONS, array(&$this, "action_admin_print_scripts"));
        add_action('admin_print_scripts-posts_page_' . CAPL_Constants::ADMIN_PAGE_OPTIONS, array(&$this, "action_admin_print_scripts"));
        add_filter("plugin_action_links_" . CAPL_PLUGIN, array($this, 'filter_plugin_action_links'));
    }

    public function action_admin_menu() {
        add_options_page("Colored Post List", "Colored Post List", "manage_options", CAPL_Constants::ADMIN_PAGE_OPTIONS, array(&$this, "view_settings"));
    }

    public function action_admin_print_scripts() {
        wp_enqueue_style("wp-color-picker");
        wp_enqueue_script("wp-color-picker");
        wp_enqueue_script("capl-settings", CAPL_PLUGIN_URL . "scripts/settings.js", array("jquery", "wp-color-picker"));
    }

    public function filter_plugin_action_links($links) {
        $settings_link = '<a href="options-general.php?page=' . CAPL_Constants::ADMIN_PAGE_OPTIONS . '">' . __("Settings", "colored-admin-post-list") . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function register_settings() {
        add_settings_section(CAPL_Constants::SETTINGS_SECTION_GENERAL, __("General", "colored-admin-post-list"), array(&$this, "settings_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT);
        add_settings_section(CAPL_Constants::SETTINGS_SECTION_COLORS_DEFAULT, __("Default Post Statuses", "colored-admin-post-list"), array(&$this, "settings_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT);

        add_settings_field(CAPL_Constants::SETTING_ENABLED, __("Enabled", "colored-admin-post-list"), array(&$this, "setting_enabled_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_GENERAL);

        $default_post_statuses = CAPL_Helper::get_post_statuses_default();

        foreach ($default_post_statuses as $custom_post_status):
            $handle = $custom_post_status["option_handle"];
            $args = array(
                'handle' => $handle
            );
            add_settings_field($handle, __($custom_post_status["label"]), array(&$this, "setting_callback_color"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS_DEFAULT, $args);
            register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, $handle, array(&$this, "setting_validate_color"));
        endforeach;

        $custom_post_statuses = CAPL_Helper::get_post_statuses_custom();

        foreach ($custom_post_statuses as $custom_post_status):
            $handle = $custom_post_status["option_handle"];
            $args = array(
                'handle' => $handle
            );
            add_settings_field($handle, __($custom_post_status["label"]), array(&$this, "setting_callback_color"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS_CUSTOM, $args);
            register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, $handle, array(&$this, "setting_validate_color"));
        endforeach;

        if (sizeof($custom_post_statuses) > 0):
            add_settings_section(CAPL_Constants::SETTINGS_SECTION_COLORS_CUSTOM, __("Custom Post Statuses", "colored-admin-post-list"), array(&$this, "settings_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT);
        endif;
    }

    public function settings_callback() {
       
    }

    public function setting_enabled_validate($input) {
        return $input;
    }

    public function setting_enabled_callback() {
        $checked = checked(get_option(CAPL_Constants::SETTING_ENABLED, false), true, false);
        echo '<input type="checkbox" name="' . CAPL_Constants::SETTING_ENABLED . '" value="1"' . $checked . '  />';
    }

    public function setting_callback_color(array $args) {
        $handle = $args["handle"];
        $setting = get_option($handle);
        echo '<input class="capl-wp-color-picker" type="text" id="' . $handle . '" class="regular-text" name="' . $handle . '" value="' . $setting . '" />';
    }

    public function setting_validate_color($input) {
        $valid = filter_var($input, FILTER_SANITIZE_STRING);

        if (!empty($valid) && CAPL_Helper::validate_html_color($valid) == false):
            add_settings_error(CAPL_Constants::SETTINGS_ERRORS, 666, __("Invalid Color", "colored-admin-post-list"), "error");
            return false;
        endif;

        return $valid;
    }

    public static function reset_colors() {
        //deprecated
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_DRAFTS);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PUBLISH);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PRIVATE);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PENDING);
        delete_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_FUTURE);

        $custom_post_statuses = CAPL_Helper::get_post_statuses_custom();

        foreach ($custom_post_statuses as $custom_post_status):
            $handle = $custom_post_status["option_handle"];
            delete_option($handle);
        endforeach;

        $default_post_statuses = CAPL_Helper::get_post_statuses_default();

        foreach ($default_post_statuses as $custom_post_status):
            $handle = $custom_post_status["option_handle"];
            delete_option($handle);
        endforeach;

        update_option("capl-color-publish", CAPL_Constants::DEFAULT_COLOR_PUBLISH);
        update_option("capl-color-draft", CAPL_Constants::DEFAULT_COLOR_DRAFTS);
        update_option("capl-color-pending", CAPL_Constants::DEFAULT_COLOR_PENDING);
        update_option("capl-color-future", CAPL_Constants::DEFAULT_COLOR_FUTURE);
        update_option("capl-color-private", CAPL_Constants::DEFAULT_COLOR_PRIVATE);
    }

    public function view_settings() {
        include(CAPL_PLUGIN_DIR . "/views/settings.php");
    }
}
