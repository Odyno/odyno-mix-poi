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

if (!class_exists("Mixare_Formatter")) :

    require_once(ODYNOMIXPOI_SHOW_DIR.'/class-formatter.php');

    class Mixare_Formatter extends Formatter {

    /**
     *
     * @param type $dataOut
     * @return type
     */
    protected function format($pixels) {
        $dataOut = Array();
        $i = 0;
        foreach ($pixels as $pixel) {
            $dataOut[$i] = array(
                'id' => $pixel->id,
                'lat' => $pixel->lat,
                'lng' => $pixel->lng,
                'elevation' => '3000',
                'title' => $pixel->dsc,
                'mMeteo' => $pixel->img_id);
            $i++;
        }

        $data = array("status" => "OK", "num_results" => count($dataOut), "results" => $dataOut);
        return $data;
    }

    }

endif;