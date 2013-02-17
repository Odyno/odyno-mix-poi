<?php

add_action('admin_menu', 'register_mixpoi_map_index_page');

function register_mixpoi_map_index_page() {
  add_menu_page('Mixare Poi- All Maps', 'MixarePOI', 'edit_posts','mixare-poi/maps/availabe.php', '', plugins_url('/carody/images/Fuel_Icon.png'));
 // add_submenu_page('mixare-poi/maps/availabe.php', 'All Maps', 'All Maps', 'edit_posts', 'mixare-poi/maps/availabe.php', '');
  
}

?>