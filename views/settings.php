<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])):
    die('Please do not load this screen directly. Thanks!');
endif;

if (isset($_POST["capl-submit-reset"])):
    CAPL_SettingsController::reset_colors();
endif;
?>

<div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php echo __("Colored Admin Post List Settings", "colored-admin-post-list") ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields(CAPL_Constants::SETTINGS_PAGE_DEFAULT); ?>
        <?php do_settings_sections(CAPL_Constants::SETTINGS_PAGE_DEFAULT); ?>
        <?php submit_button(); ?>
    </form>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>?page=capl_admin_options" id="capl-form-reset-to-defaults">
        <?php submit_button(__("Reset Settings", "colored-admin-post-list"), "delete", "capl-submit-reset", true, array("id" => "capl-button-reset-to-defaults", "data-message" => __("Are you sure?", "colored-admin-post-list"))); ?>

    </form>


</div>
