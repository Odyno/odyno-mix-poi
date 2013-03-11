<?php

/**
 * Manage All action of Maps
 */
if (!class_exists('OMP_Poi_View')) {

    class OMP_Poi_View
    {




        static function get_poi_list($map_id)
        {
            global $wpdb;
            $tablePoiHasMap = $wpdb->prefix . "omp_poi_has_map";
            $table = $wpdb->prefix . "omp_poi";
            $listOfPoiId = "SELECT poi_poi_id FROM `" . $tablePoiHasMap . "` where map_map_id = $map_id";

            $sql = "SELECT poi_id, point_id, title, url  FROM `" . $table . "` where poi_id in ( $listOfPoiId ) ";
            $rowDatas = $wpdb->get_results($sql, ARRAY_A);

            foreach ($rowDatas as $row) {
                $pointData = OMP_Location_Manager::get($row['point_id']);
                $row['lat'] = $pointData['lat'];
                $row['lng'] = $pointData['lng'];
                $row['elev'] = $pointData['elev'];
            }


            return $rowDatas;
        }


        static function get_poi_from_db($poi_id){
            global $wpdb;
            $table = $wpdb->prefix . "omp_poi";
            $sql = "SELECT poi_id, point_id, title, url  FROM `" . $table . "` where poi_id = $poi_id";
            $rowDatas = $wpdb->get_results($sql, ARRAY_A);
            if (isset($rowDatas) && !empty($rowDatas)){
                $row=$rowDatas[0];
                $pointData = OMP_Location_Manager::get($row['point_id']);
                $rowDatas[0]['lat'] = $pointData['lat'];
                $rowDatas[0]['lng'] = $pointData['lng'];
                $rowDatas[0]['elev'] = $pointData['elev'];
            }
            return $rowDatas;
        }

    }

}
?>
