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

if (!class_exists("Formatter")) :

    require_once(ODYNOMIXPOI_SHOW_DIR . '/class-pixel-data-provider.php');

    class Formatter extends Pixel_Data_Provider
    {

        /**
         * Return one array of PixelMeteo. It's the rapresentation of area with box from minimum longitude, latitude, maximum longitude and latitude, respectively.
         * @param type $minlatitude
         * @param type $minlongitude
         * @param type $maxlatitude
         * @param type $maxlongitude
         * @return type
         */
        public function showPixelsOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude)
        {
            $dataOut = $this->getPixelsOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude);

            $this->show($this->format($dataOut));
        }

        /**
         * Show the information and close the connection
         *
         * @param type $latitude
         * @param type $longitude
         * @param type $dis_in_km
         */
        public function showPixelsNear($latitude, $longitude, $dis_in_km)
        {
            $dataOut = $this->getPixelsNear($latitude, $longitude, $dis_in_km);

            $this->show($this->format($dataOut));
        }

        /**
         *
         * @param type $dataOut
         */
        private function show($dataOut)
        {
            header('Content-type: application/json');
            echo json_encode($dataOut);
            exit;
        }

        /**
         * function to override if you want new format of json data
         *
         * @param type $dataOut
         * @return type
         */
        protected function format($dataOut)
        {
            $aa = array("num_results" => count($dataOut), "results" => $dataOut);
            return $aa;
        }

    }

endif;