<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-dashboard-widget-controller
 *
 * @author Stevie
 */
class CAPL_DashboardWidgetController {

    function __construct() {
        add_action('wp_dashboard_setup', array(&$this, "dashboard_setup"));
    }

    public function dashboard_setup() {
        wp_add_dashboard_widget("capl_dashboard_widget", __("Recent Drafts by CAPL", CAPL_TEXTDOMAIN), array(&$this, "thewidget"));
    }

    public function thewidget() {

        require_once(CAPL_PLUGIN_DIR . "/views/dbwidget-recent-drafts.php");
        
    }

}

?>
