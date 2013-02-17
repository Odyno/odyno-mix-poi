<?php

//retrieve handler of life cycle

require_once ODYNOMIXPOI_DIR.'/install/class-odynomixpoi-lifecycle-handler.php';

function odynomixpoi_on_active() {
    $me = new Odynomixpoi_Lifecycle_Handler('activate');
    add_action('admin_notices', array(&$me, 'show_message_cb'));
}

function odynomixpoi_on_deactive() {
    $me = new Odynomixpoi_Lifecycle_Handler('deactivate');
    add_action('admin_notices', array(&$me, 'show_message_cb'));
}

function odynomixpoi_on_uninstall() {
    $me = new Odynomixpoi_Lifecycle_Handler('uninstall');
    add_action('admin_notices', array(&$me, 'show_message_cb'));
}

register_activation_hook(ODYNOMIXPOI__FILE__, 'odynomixpoi_on_active');
register_deactivation_hook(ODYNOMIXPOI__FILE__, 'odynomixpoi_on_deactive');
register_uninstall_hook(ODYNOMIXPOI__FILE__, 'odynomixpoi_on_uninstall');


?>