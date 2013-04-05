<?php

/**
 * Manage All action of poi
 */
if (!class_exists('OMP_Poi_Manager')) {

    require_once ODYNOMIXPOI_DIR."/pois/class-omp-location-manager.php";
    require_once ODYNOMIXPOI_DIR."/pois/class-omp-poi-view.php";
    require_once ODYNOMIXPOI_DIR."/maps/class-omp-maps-manager.php";


    class OMP_Poi_Manager
    {

        var $wpdb, $databasePre;

        function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->databasePre = $wpdb->prefix . "omp_";

        }

        function applayAction($commands)
        {
            try {
                if (isset($commands['do'])) {
                    $do = @$commands['do'] . "_action";
                    $fnc = new ReflectionMethod('OMP_Poi_Manager', $do);
                    $fnc->invoke($this, $commands);
                }
            } catch (Exception $e) {
                if (WP_DEBUG) {
                    wp_die("Action not Allowed!:" . $e->__toString());
                } else {
                    wp_die("Action not Allowed!");
                }
            }
        }

        function delete_action($commands)
        {

        }

        function edit_action($commands)
        {

        }

        function insert_action($commands)
        {

        }

        static function insert($title, $point_id, $url = null, $post_id = null, $map_id = null)
        {
            global $wpdb;
            $poi_id = null;
            $poi_id = $wpdb->insert(
                $wpdb->prefix . "omp_poi",
                array(
                    'point_id' => $point_id,
                    'title' => $title,
                    'url' => $url,
                    'post_id' => $post_id
                )
            );
            if ($poi_id != null) {
                //aggiungilo dentro ad una mappa
                OMP_Maps_Manager::assoc($poi_id, $map_id);
            }
            return $poi_id;
        }


        static function insert_with_position($title, $lat, $lng, $url = null, $post_id = null, $map_id = null)
        {
            global $wpdb;
            $poi_id = null;
            $point = OMP_Location_Manager::getPoint($lat, $lng);
            if (isset($point)) {
                $point_id = $point['0']['point_id'];
            } else {
                $point_id = OMP_Location_Manager::save($lat, $lng, null);
            }
            if ($point_id != null) {
                $poi_id = self::insert($title, $point_id, $url, $post_id, $map_id);
            }
            return $poi_id;
        }


        static function remove($poi_id, $map_id)
        {
            global $wpdb;
            $poi_table=$wpdb->prefix . "omp_poi";
            $poi=OMP_Poi_View::get_row_poi_from_db($poi_id);

            if (isset($poi[0])){
                OMP_Location_Manager::delete($poi[0]['point_id']);
                $wpdb->query($wpdb->prepare(
                        "DELETE FROM $poi_table WHERE poi_id = %d ",
                        $poi_id
                    )
                );
                OMP_Maps_Manager::deassoc($poi_id,$map_id);
            }
        }

    }

}
?>
