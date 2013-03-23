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

if (!class_exists("Panoramio_Formatter")) :


    require_once(ODYNOMIXPOI_SHOW_DIR.'/class-formatter.php');


    class Panoramio_Formatter extends Formatter {

        function format($meteoPixels) {
            $dataOut = Array();
            $i = 0;
            foreach ($meteoPixels as $pixelMeteo) {
                $dataOut[$i] = array(
                    'photo_id' => $pixelMeteo->id,
                    'photo_title' => $pixelMeteo->dsc,
                    'photo_url' => 'http://www.mondometeo.org/',
                    'photo_file_url' => 'http://www.mondometeo.org/mm-site/img/weather-icons/' . $pixelMeteo->img_id . '.png',
                    'latitude' => $pixelMeteo->lat,
                    'longitude' => $pixelMeteo->lng,
                    'width' => '32',
                    'height' => '32',
                    "upload_date" => "22 January 2007",
                    "owner_id" => '1',
                    "owner_name" => "mondometeo.org",
                    "owner_url" => "http://www.mondometeo.org/");
                $i++;
            }

            $data = array("count" => count($dataOut), "photos" => $dataOut);
            return $data;
        }

    }

endif;