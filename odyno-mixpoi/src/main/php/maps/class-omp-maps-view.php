<?php

/**
 * Manage All action of Maps
 */
if (!class_exists('OMP_Maps_View')) {

  class OMP_Maps_View {

    static function get_maps_list_from_db($user_id){
        global $wpdb;
        $sql = "SELECT map_id, name, utente_id  FROM `" . $wpdb->prefix . "omp_map` where utente_id = $user_id";
        $out = $wpdb->get_results($sql, ARRAY_A);
        return $out;
    }

      static function get_maps_from_db($map_id,$user_id=null){
          global $wpdb;
          if ($user_id == null){
              $user_id= get_current_user_id();
          }
          $sql = "SELECT map_id, name, utente_id  FROM `" . $wpdb->prefix . "omp_map` where utente_id = $user_id and map_id = $map_id";
          $out = $wpdb->get_results($sql, ARRAY_A);
          return $out;
      }

  }

}
?>
