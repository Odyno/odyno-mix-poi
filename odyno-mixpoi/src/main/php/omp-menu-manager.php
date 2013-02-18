<?php

add_action('admin_menu', 'register_omp_menu_index_page');


function register_omp_menu_index_page() {
  add_menu_page('Odyno MixPOI', 'Odyno MixPOI', 'add_users', 'odyno-mixpoi/maps/maps-index.php','', plugins_url('/odyno-mixpoi/res/icon_datasource16x16.png'));
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', "Yours DataSource", 'Yours DataSource', 'add_users', 'odyno-mixpoi/maps/maps-index.php','');
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', 'Add new DataSource', 'Add new DataSource', 'add_users', 'odyno-mixpoi/maps/maps-mng.php', '');
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', 'Manage DataSource', 'Manage DataSource', 'add_users', 'odyno-mixpoi/maps/poi-index.php', '');
  add_submenu_page('odyno-mixpoi/maps/maps-index.php', 'Add new POI', 'Add new POI', 'add_users', 'odyno-mixpoi/maps/poi-mng.php', '');

}




?>