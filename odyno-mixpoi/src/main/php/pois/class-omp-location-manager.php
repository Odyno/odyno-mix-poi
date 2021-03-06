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

if (!class_exists("OMP_Location_Manager")) :

    require_once ODYNOMIXPOI_DIR . "/pojo/class-omp-point.php";

    class OMP_Location_Manager
    {

        /**
         * Gets object from database
         * @param integer $temperaturaId
         * @return object $temperatura
         */
        public static function get($point_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "omp_point";
            $sql = "select point_id, X(location) as lat, Y(location) as lng , elevation from `$table` where `point_id`='" . intval($point_id) . "' LIMIT 1";
            return $wpdb->get_results($sql, ARRAY_A);
        }

        public static function getPoint($lat, $lng)
        {
            self::getList($lat, $lng, 1);
        }


        public static function getList($lat, $lng, $num = null)
        {
            $filter[0] = "X(location) = " . $lat;
            $filter[1] = "Y(location) = " . $lng;
            return self::getListWithRowWhere($filter, $num);
        }

        /**
         * Returns a sorted array of objects that match given conditions
         * @param multidimensional array {"field comparator value", "field comparator value", ...}
         * @param string $sortBy
         * @param boolean $ascending
         * @param int limit
         * @return array $temperaturaList
         */
        public static function getListWithRowWhere($fcv_array = array(), $num = null)
        {
            global $wpdb;
            $table = $wpdb->prefix . "omp_point";
            $puntoList = array();
            $sql = "select point_id, X(location) as lat, Y(location) as lng , elevation from `$table` ";
            $temperaturaList = Array();
            if (sizeof($fcv_array) > 0) {
                $sql .= " where " . $fcv_array[0];

                $query = "";

                for ($i = 1, $c = sizeof($fcv_array); $i < $c; $i++) {
                    $query .= " AND " . $fcv_array[$i];
                }

                $sql .= $query;
                if ($num == null) {
                    $sql .= ' LIMIT 0, ' . $num;
                }
            }
            return $wpdb->get_results($sql, ARRAY_A);
        }


        /**
         * Find points in a bounding box
         * SET @bbox = 'POLYGON((0 0, 10 0, 10 10, 0 10, 0 0))';
         * SELECT name, AsText(location) FROM Points WHERE Intersects( location, GeomFromText(@bbox) );
         *
         * @param array Of object  PointLocation
         */
        public static function getFromBoundingBox($arrayOfCouple = array())
        {
            $pt = "";
            $i = -1;
            foreach ($arrayOfCouple as $point) {
                $i++;
                $pt[$i] = $point[0] . " " . $point[1];

            }
            $where = null;
            if ($i != -1) {
                $comma_separated = implode(",", $pt);
                $where[0] = " Intersects( location, GeomFromText('POLYGON((" . $comma_separated . "))') ) ";

            }
            return self::getListWithRowWhere($where);
        }

        /**
         *
         * BUONA PER TUTTI I PUNTI
         *
         * set @alat=41.799002766525;
         * set @alng=12.428941906807;
         * set @dis=0.08;
         * SELECT pointid, X(location) as lat, Y(location) as lng,
         * ((acos(sin((PI() * X(location) / 180)) * sin((PI() * @alat / 180)) + cos((PI() * X(location) / 180)) * cos((PI() * @alat / 180)) * cos((ABS(  (PI() * @alng / 180) - (PI() * Y(location) / 180) ))))) * 6372.795477598) as distance
         * FROM pointlocation WHERE
         * ((acos(sin((PI() * X(location) / 180)) * sin((PI() * @alat / 180)) + cos((PI() * X(location) / 180)) * cos((PI() * @alat / 180)) * cos((ABS(  (PI() * @alng / 180) - (PI() * Y(location) / 180) ))))) * 6372.795477598) < @dis ;
         *
         */
        public static function getPoints($lat, $long, $dis_in_km)
        {
            global $wpdb;
            $table = $wpdb->prefix . "omp_point";

            $sql = "
SELECT point_id, X(location) as lat, Y(location) as lng, elevation
((acos(sin((PI() * X(location) / 180)) * sin((PI() * " . $lat . " / 180)) + cos((PI() * X(location) / 180)) * cos((PI() * " . $lat . " / 180)) * cos((ABS(  (PI() * " . $long . " / 180) - (PI() * Y(location) / 180) ))))) * 6372.795477598) as distance
FROM `$table` WHERE
((acos(sin((PI() * X(location) / 180)) * sin((PI() * " . $lat . " / 180)) + cos((PI() * X(location) / 180)) * cos((PI() * " . $lat . " / 180)) * cos((ABS(  (PI() * " . $long . " / 180) - (PI() * Y(location) / 180) ))))) * 6372.795477598) < " . $dis_in_km . " ORDER BY distance;
";
            return $wpdb->get_results($sql, ARRAY_A);

        }


        /**
         * Saves the object to the database
         * @return integer $puntoId
         */
        public static function save($lat, $lng, $ele_in_km = 0)
        {
            global $wpdb;
            $table = $wpdb->prefix . "omp_point";
            $sql=$wpdb->prepare(
                "insert into $table ( location  , elevation  ) values ( Point( %f , %f ) , %d )",
                array(
                    $lat,
                    $lng,
                    $ele_in_km
                )
            );

             $wpdb->query($sql);
            $out=$wpdb->insert_id;

            return $out;
        }

        /**
         * Deletes the object from the database
         * @return boolean
         */
        public static function delete($point_id)
        {
            global $wpdb;
            $table = $wpdb->prefix . "omp_point";
            $wpdb->query($wpdb->prepare("DELETE FROM $table WHERE point_id = %s", $point_id));

        }

    }

endif;

?>