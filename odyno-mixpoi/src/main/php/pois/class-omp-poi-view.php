<?php

/**
 * Manage All action of Maps
 */
if (!class_exists('OMP_Poi_View')) {

    class OMP_Poi_View
    {

        static function get_row_poi_from_db($poi_id)
        {
            global $wpdb;
            $tablePoi = $wpdb->prefix . "omp_poi";

            $query = ($wpdb->prepare("
SELECT
  poi.poi_id         as id,
  poi.point_id       as point_id,
  poi.title          as title,
  poi.url            as url,
  poi.post_id        as post_id
FROM
  $tablePoi  as poi
where
   poi.poi_id = %s
	            ",
                array(
                    $poi_id,
                )
            ));

            return $wpdb->get_results($query, ARRAY_A);
        }



        static function get_poi_from_db($poi_id)
        {
            global $wpdb;
            $tablePoint = $wpdb->prefix . "omp_point";
            $tablePoi = $wpdb->prefix . "omp_poi";

            $query = ($wpdb->prepare("
SELECT
  poi.poi_id         as id,
  X(loc.location)    as lat,
  Y(loc.location)    as lng ,
  loc.elevation      as elevation,
  poi.title          as title,
  poi.url            as url,
  poi.post_id        as post_id
FROM
  $tablePoint  as loc ,
  $tablePoi  as poi
where
   poi.point_id = loc.point_id
   and poi.poi_id = %s
	            ",
                array(
                    $poi_id,
                )
            ));

            $pointData = $wpdb->get_results($query, ARRAY_A);
            $point = new OMP_Point();
            if (isset($pointData[0])) {
                $point->id = $pointData[0]['id'];
                $point->lat = $pointData[0]['lat'];
                $point->lng = $pointData[0]['lng'];
                $point->elev = $pointData[0]['elevation'];
                $point->title = $pointData[0]['title'];
                $point->url = $pointData[0]['url'];
                $point->post_id = $pointData[0]['post_id'];
            }
            return $point;
        }


        static function  get_pois_from_points($list_of_point_id = ' 1 ')
        {
            global $wpdb;
            $tablePoint = $wpdb->prefix . "omp_point";
            $tablePoi = $wpdb->prefix . "omp_poi";

            $query = '
SELECT
  poi.poi_id         as id,
  X(loc.location)    as lat,
  Y(loc.location)    as lng ,
  loc.elevation      as elevation,
  poi.title          as title,
  poi.url            as url,
  poi.post_id        as post_id
FROM
  ' . $tablePoint . '  as loc ,
  ' . $tablePoi . '  as poi
where
   poi.point_id = loc.point_id
   and loc.point_id in ( ' . $list_of_point_id . ')';

            $poisDatas = $wpdb->get_results($query, ARRAY_A);
            $out = array();

            if (isset($poisDatas[0])) {
                for ($int = 0; $int < count($poisDatas); $int++) {
                    $point = new OMP_Point();
                    $point->id = $poisDatas[$int]['id'];
                    $point->lat = $poisDatas[$int]['lat'];
                    $point->lng = $poisDatas[$int]['lng'];
                    $point->elev = $poisDatas[$int]['elevation'];
                    $point->title = $poisDatas[$int]['title'];
                    $point->url = $poisDatas[$int]['url'];
                    $point->post_id = $poisDatas[$int]['post_id'];
                    $out[$int] = $point;
                }
            }
            return $out;
        }

        static function get_pois_from_point($lat, $long, $dis_in_km)
        {
            return null;
        }

        static function  get_pois_from_bounding_box($arrayOfCouple = array())
        {
            return null;
        }

        static function get_poi_list($map_id)
        {
            return null;
        }

    }

}
?>
