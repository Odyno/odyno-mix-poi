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

if (!class_exists("OMP_Post_Management")) :

    require_once ODYNOMIXPOI_DIR . "/maps/class-omp-location-manager.php";


    class OMP_Post_Management
    {

        static function get_point_of_post($post_id)
        {


            global $wpdb;
            $tablePoiHasMap = $wpdb->prefix . "omp_post_has_point";
            $listOfPoiId = "SELECT point_point_id FROM `" . $tablePoiHasMap . "` where post_post_id = $post_id ";
            $rowDatas = $wpdb->get_results($listOfPoiId, ARRAY_A);

            if (isset($rowDatas[0])) {
                $pointData = OMP_Location_Manager::get($rowDatas[0]['point_point_id']);
                $row['lat'] = $pointData[0]['lat'];
                $row['lng'] = $pointData[0]['lng'];
                $row['elev'] = $pointData[0]['elev'];


            } else {
                $row['lat'] = 0.0;
                $row['lng'] = 0.0;
                $row['elev'] = 0.0;
            }
            return $row;
        }

        static function unlink_to_post_action($post_id)
        {
//            wp_die("B- $post_id");
            global $wpdb;
            $point_id = self::get_point_of_post($post_id);
            $table = $wpdb->prefix . "omp_post_has_point";
            $wpdb->query($wpdb->prepare("DELETE FROM %s WHERE post_post_id = %s",$table, $post_id));
            OMP_Location_Manager::delete($point_id);

        }

        static function link_to_post_action($post_id, $lat, $lng)
        {
           self::unlink_to_post_action($post_id);
           $point_point_id =OMP_Location_Manager::save($lat, $lng, null);
           global $wpdb;
           $rows_affected = $wpdb->insert($wpdb->prefix . "omp_post_has_point", array('point_point_id' => $point_point_id, 'post_post_id' => $post_id));
        }


    }

endif;