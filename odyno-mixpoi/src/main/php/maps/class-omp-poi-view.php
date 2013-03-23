<?php

/**
 * Manage All action of Maps
 */
if (!class_exists('OMP_Poi_View')) {

    class OMP_Poi_View
    {

        static function get_poi_list_from_point($arrayOfPointId = array(), $map_id=null){
            $pointTofind = "";
            foreach ($arrayOfPointId as $point) {
                $pointTofind .= "$point,";
            }

            $pointTofind = rtrim($pointTofind, ',');
            $whereCondition = " `pointid` in (" . $pointTofind . ") ";


            $out='
SELECT
  map.map_map_id     as map_id,
  post.post_post_id  as post_id,
  X(loc.location)    as lat,
  Y(loc.location)    as lng ,
  loc.elevation      as elevation,
  poi.title          as title,
  poi.url            as url
FROM
  wp_omp_point as loc ,
  wp_omp_poi as poi ,
  wp_omp_poi_has_map as map ,
  wp_omp_post_has_point as post
where
   poi.point_id = loc.point_id
   and poi.poi_id = map.poi_poi_id
   and post.point_point_id = loc.point_id
   and loc.point_id in ( 32, 33 , 34 )
   ';

        }


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
