<?php

/**
 * Description of class-formatter
 *
 * @author Alessandro Staniscia
 */
class PanoramioFormatter extends Formatter {

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

?>
