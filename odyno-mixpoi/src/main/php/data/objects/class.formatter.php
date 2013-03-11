<?php

/**
 * Description of class-formatter
 *
 * @author Alessandro Staniscia
 */
class Formatter extends PixelMeteoDataProvider {

    /**
     * Return one array of PixelMeteo. It's the rapresentation of area with box from minimum longitude, latitude, maximum longitude and latitude, respectively.
     * @param type $minlatitude
     * @param type $minlongitude
     * @param type $maxlatitude
     * @param type $maxlongitude
     * @return type
     */
    public function showPixelMeteoOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude) {
        $dataOut = $this->getPixelMeteoOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude);
        
        $this->show($this->format($dataOut));
    }

    /**
     * Show the information and close the connection 
     * 
     * @param type $latitude
     * @param type $longitude
     * @param type $dis_in_km

     */
    public function showPixelMeteoNear($latitude, $longitude, $dis_in_km) {
        $dataOut = $this->getPixelMeteoNear($latitude, $longitude, $dis_in_km);
        
        $this->show($this->format($dataOut));
    }

    /**
     * 
     * @param type $dataOut
     */
    private function show($dataOut) {
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
    protected function format($dataOut) {
        $aa= array("num_results" => count($dataOut), "results" => $dataOut);
        return $aa;
    }

}

?>
