<?php

/**
 * Description of class-formatter
 *
 * @author Alessandro Staniscia
 */
class MixareFormatter extends Formatter {

    /**
     * 
     * @param type $dataOut
     * @return type
     */
    protected function format($meteoPixels) {
        $dataOut = Array();
        $i = 0;
        foreach ($meteoPixels as $pixelMeteo) {
            $dataOut[$i] = array(
                'id' => $pixelMeteo->id,
                'lat' => $pixelMeteo->lat,
                'lng' => $pixelMeteo->lng,
                'elevation' => '3000',
                'title' => $pixelMeteo->dsc,
                'mMeteo' => $pixelMeteo->img_id);
            $i++;
        }

        $data = array("status" => "OK", "num_results" => count($dataOut), "results" => $dataOut);
        return $data;
    }

}

?>
