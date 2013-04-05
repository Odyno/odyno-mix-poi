<?php
/*  
    Copyright 2012  Alessandro Staniscia ( alessandro@staniscia.net )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("OMP_Post_Manager")) :

    require_once ODYNOMIXPOI_DIR . "/pois/class-omp-location-manager.php";
    require_once ODYNOMIXPOI_DIR . "/pois/class-omp-poi-manager.php";
    require_once ODYNOMIXPOI_DIR . "/pojo/class-omp-point.php";


    class OMP_Post_Manager extends OMP_Poi_Manager
    {

        /**
         * Return location of post
         *
         * @param $post_id
         * @return OMP_Point
         */
        static function get_poi_of_post($post_id)
        {
            global $wpdb;
            $tablePoint = $wpdb->prefix . "omp_point";
            $tablePoi = $wpdb->prefix . "omp_poi";

            $query = ($wpdb->prepare(
                "
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
   and poi.post_id = %s
	            ",
                array(
                    $post_id
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

        /**
         * Remove location at post
         *
         * @param $post_id
         */
        static function unlink_to_post_action($post_id, $map_id = null)
        {
            $poi = self::get_poi_of_post($post_id);
            self::remove($poi->id, $map_id);
        }


        /**
         * Add location at Post
         *
         * @param $post_id
         * @param $lat
         * @param $lng
         */
        static function link_to_post_action($post_id, $lat, $lng, $map_id = null)
        {
            return self::insert_with_position("-", $lat, $lng, null, $post_id, $map_id);
        }
    }
endif;