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

if (!class_exists("Odynomixpoi_db")) :

    class Odynomixpoi_db
    {

        static function DDLs($databasePre="") {
            $dbSchema = array(
                $databasePre . "omp_point" => "CREATE TABLE `" . $databasePre . "omp_point` ( `point_id` INT NOT NULL AUTO_INCREMENT , `location` POINT NULL , `elevation` INT NOT NULL DEFAULT 0 , PRIMARY KEY (`point_id`) ) ",
                $databasePre . "omp_poi" => "CREATE TABLE `" . $databasePre . "omp_poi` ( `poi_id` INT NOT NULL AUTO_INCREMENT , `point_id` INT NOT NULL , `title` VARCHAR(100) NULL , `url` VARCHAR(250) NULL , PRIMARY KEY (`poi_id`, `point_id`) ) ",
                $databasePre . "omp_map" => "CREATE TABLE `" . $databasePre . "omp_map` ( `map_id` INT NOT NULL AUTO_INCREMENT , `utente_id` INT NULL , `name` VARCHAR(45) NULL , PRIMARY KEY (`map_id`) ) ",
                $databasePre . "omp_poi_has_map" => "CREATE TABLE `" . $databasePre . "omp_poi_has_map` ( `poi_poi_id` INT NOT NULL , `map_map_id` INT NOT NULL , PRIMARY KEY (`poi_poi_id`, `map_map_id`) ) ",            );
            return $dbSchema;
        }

    }

endif;