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
        $settings_link = '<a href="options-general.php?page=' . CAPL_Constants::ADMIN_PAGE_OPTIONS . '">' . __("Settings", CAPL_Constants::TEXT_DOMAIN) . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function register_settings() {
        add_settings_section(CAPL_Constants::SETTINGS_SECTION_DEFAULT, __("Settings", CAPL_Constants::TEXT_DOMAIN), array(&$this, "settings_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT);
        add_settings_section(CAPL_Constants::SETTINGS_SECTION_COLORS, __("Colors", CAPL_Constants::TEXT_DOMAIN), array(&$this, "settings_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT);

        add_settings_field(CAPL_Constants::SETTING_ENABLED, __("Enabled", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_enabled_callback"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_DEFAULT);
        add_settings_field(CAPL_Constants::SETTING_COLOR_DRAFTS, __("Drafts", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_callback_color_drafts"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS);
        add_settings_field(CAPL_Constants::SETTING_COLOR_PENDING, __("Pending", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_callback_color_pending"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS);
        add_settings_field(CAPL_Constants::SETTING_COLOR_FUTURE, __("Future", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_callback_color_future"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS);
        add_settings_field(CAPL_Constants::SETTING_COLOR_PRIVATE, __("Private", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_callback_color_private"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS);
        add_settings_field(CAPL_Constants::SETTING_COLOR_PUBLISH, __("Publish", CAPL_Constants::TEXT_DOMAIN), array(&$this, "setting_callback_color_publish"), CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTINGS_SECTION_COLORS);

        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_ENABLED, array(&$this, "setting_enabled_validate"));
        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_COLOR_DRAFTS, array(&$this, "setting_validate_color"));
        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_COLOR_PENDING, array(&$this, "setting_validate_color"));
        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_COLOR_FUTURE, array(&$this, "setting_validate_color"));
        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_COLOR_PRIVATE, array(&$this, "setting_validate_color"));
        register_setting(CAPL_Constants::SETTINGS_PAGE_DEFAULT, CAPL_Constants::SETTING_COLOR_PUBLISH, array(&$this, "setting_validate_color"));
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

    public function setting_callback_color_drafts() {
        $this->setting_callback_color(CAPL_Constants::SETTING_COLOR_DRAFTS, CAPL_Constants::DEFAULT_COLOR_DRAFTS);
    }

    public function setting_callback_color_pending() {
        $this->setting_callback_color(CAPL_Constants::SETTING_COLOR_PENDING, CAPL_Constants::DEFAULT_COLOR_PENDING);
    }

    public function setting_callback_color_future() {
        $this->setting_callback_color(CAPL_Constants::SETTING_COLOR_FUTURE, CAPL_Constants::DEFAULT_COLOR_FUTURE);
    }

    public function setting_callback_color_private() {
        $this->setting_callback_color(CAPL_Constants::SETTING_COLOR_PRIVATE, CAPL_Constants::DEFAULT_COLOR_PRIVATE);
    }

    public function setting_callback_color_publish() {
        $this->setting_callback_color(CAPL_Constants::SETTING_COLOR_PUBLISH);
    }

    private function setting_callback_color($setting_name, $default = "") {
        $setting = get_option($setting_name, $default);
        echo '<input class="capl-wp-color-picker" type="text" id="' . $setting_name . '" class="regular-text" name="' . $setting_name . '" value="' . $setting . '" />';
    }

    public function setting_validate_color($input) {
        $valid = filter_var($input, FILTER_SANITIZE_STRING);

        if (!empty($valid) && CAPL_Helper::validate_html_color($valid) == false):
            add_settings_error(CAPL_Constants::SETTINGS_ERRORS, 666, __("Invalid Color", CAPL_Constants::TEXT_DOMAIN), "error");
            return false;
        endif;

        return $valid;
    }

    public static function reset_colors() {
        delete_option(CAPL_Constants::SETTING_COLOR_DRAFTS);
        delete_option(CAPL_Constants::SETTING_COLOR_PUBLISH);
        delete_option(CAPL_Constants::SETTING_COLOR_PRIVATE);
        delete_option(CAPL_Constants::SETTING_COLOR_PENDING);
        delete_option(CAPL_Constants::SETTING_COLOR_FUTURE);
    }

    public function view_settings() {
        include(CAPL_PLUGIN_DIR . "/views/settings.php");
    }

}

?>
