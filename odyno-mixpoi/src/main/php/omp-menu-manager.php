<?php

add_action('admin_menu', 'register_omp_menu_index_page');


function register_omp_menu_index_page() {
  add_menu_page('Odyno MixPOI', 'Odyno MixPOI', 'add_users', 'odyno-mixpoi/maps/maps-index.php','', plugins_url('/odyno-mixpoi/res/icon_datasource16x16.png'));
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', "Yours Maps", 'Yours Maps', 'add_users', 'odyno-mixpoi/maps/maps-index.php','');
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', 'Add new', 'New', 'add_users', 'odyno-mixpoi/maps/maps-mng.php', '');

}




?>