<?php

require_once('../cfg.php');

addProbe();

class Pixel_Meteo {

    var $lat,
            $lng,
            $title,
            $img_url,
            $id;

}

class JSON_FACTORY_a {
    

    
    
    
     public static function showDataOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude, $type = "mixare") {
        $outs = self::getPixelMeteoNear($minlatitude, $minlongitude, $maxlatitude, $maxlongitude);
        switch ($type) {
            case "panoramio":
                $data = self::formatPanoramioMode($outs);
                break;
            default:
                $data = self::formatMixareMode($outs);
                break;
        }

        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Show the information and close the connection 
     * 
     * @param type $latitude
     * @param type $longitude
     * @param type $dis_in_km
     * @param type $type of fromat [panoramio: formato PANORAMIO, mixare: Mixare format (default)]
     */
    public static function showDataNear($latitude, $longitude, $dis_in_km, $type = "mixare") {
        $outs = self::getPixelMeteoNear($latitude, $longitude, $dis_in_km);
        switch ($type) {
            case "panoramio":
                $data = self::formatPanoramioMode($outs);
                break;
            default:
                $data = self::formatMixareMode($outs);
                break;
        }

        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Return one array of PixelMeteo. It's the rapresentation of area with box from minimum longitude, latitude, maximum longitude and latitude, respectively.
     * @param type $minlatitude
     * @param type $minlongitude
     * @param type $maxlatitude
     * @param type $maxlongitude
     * @return type
     */
    public static function getPixelMeteoOnBoundingBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude) {
        $point = new PointLocation();
        $point->lat = $minlatitude;
        $point->long = $minlongitude;
        return self::_getDataNear($point, $dis_in_km);
    }

    /**
     * Return one array of PixelMeteo. It's the rapresentation of area with center $latitude, $longitude and radius $dis_in_km
     * 
     * @param long $latitude
     * @param long $longitude
     * @param int $dis_in_km
     * @return array of \Pixel_Meteo
     */
    public static function getPixelMeteoNear($latitude, $longitude, $dis_in_km) {
        $point = new PointLocation();
        $point->lat = $latitude;
        $point->long = $longitude;
        return self::_getDataNear($point, $dis_in_km);
    }

    /**
     * Return the Mixare.org rapresentation of MeteoData.
     * 
     * @param array $meteoPixels
     * @return array mixare Format 
     */
    public static function formatPanoramioMode($meteoPixels) {
        $dataOut = Array();
        $i = 0;
        foreach ($meteoPixels as $pixelMeteo) {
            $dataOut[$i] = array(
                'photo_id' => $pixelMeteo->id,
                'photo_title' => $pixelMeteo->title,
                'photo_url' => 'http://www.mondometeo.org/',
                'photo_file_url' => 'http://www.mondometeo.org/mm-site/img/weather-icons/' . $pixelMeteo->img_url . '.png',
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

    /**
     * Return the Mixare.org rapresentation of MeteoData.
     * 
     * @param array $meteoPixels
     * @return array mixare Format 
     */
    public static function formatMixareMode($meteoPixels) {
        $dataOut = Array();
        $i = 0;
        $numOfElem = count($meteoPixels);

        foreach ($meteoPixels as $pixelMeteo) {
            $dataOut[$i] = array(
                'id' => $pixelMeteo->id,
                'lat' => $pixelMeteo->lat,
                'lng' => $pixelMeteo->lng,
                'elevation' => self::_calcElevation($lat, $lon, $alt, $dis_in_km, $numOfElem, $pixelMeteo->lat, $pixelMeteo->lng),
                'title' => $pixelMeteo->title,
                'mMeteo' => $pixelMeteo->img_url);
            $i++;
        }

        $data = array("status" => "OK", "num_results" => count($dataOut), "results" => $dataOut);
        return $data;
    }

   
    
    private static function _getDataFomBox(PointLocation $p, $dis_in_km) {
        
    }
    
private static function _getDataNear(PointLocation $p, $dis_in_km) {
    
}
    
    /**
     * Internal function used to extract data
     * 
     * @param PointLocation $p
     * @param type $dis_in_km
     * @return \Pixel_Meteo
     */
    private static function _getDataNear(PointLocation $p, $dis_in_km) {
        $out = array();
        $p1 = new PointLocation();
        $outpoints = $p1->getPoints($p, $dis_in_km);

        $pointTofind = "";
        foreach ($outpoints as $point) {
            $pointTofind .= "$point[3],";
        }
        //$pointTofind .="''";
        $pointTofind = rtrim($pointTofind, ',');

        $rptd = new RPTD();
        $lastrptd = $rptd->getLastRptd();

        $whereCondition = " `rptdid` = " . $lastrptd->rptdId . " AND `pointid` in (" . $pointTofind . ") ";

        $temperature = new Temperature();
        $rr = new RainRate();
        $cld = new Cloud();
        $allTempterature = $temperature->GetByPoints($whereCondition);
        $allRR = $rr->GetByPoints($whereCondition);
        $allCld = $cld->GetByPoints($whereCondition);

        $i = 0;
        foreach ($allTempterature as $bus) {
            $obj = new Pixel_Meteo();
            $oooo = $p1->Get($bus->pointid);
            $obj->id = $bus->pointid;
            $obj->lat = $oooo->lat;
            $obj->lng = $oooo->long;
            $obj->title = self::formatLabel($bus->pointid, $allTempterature, $allRR, $allCld, $lastrptd->date, $lastrptd->time);
            $obj->img_url = self::formatUrl($bus->pointid, $allRR, $allCld);
            $out[$i] = $obj;
            $i++;
        }
        return $out;
    }

    /**
     *  internal and fake function used to calculate the elevation of objects
     * 
     * @param type $lat
     * @param type $lon
     * @param type $alt
     * @param type $dis_in_km
     * @param type $numOfElem
     * @param type $poi_lat
     * @param type $poi_lng
     * @return int
     */
    private static function _calcElevation($lat, $lon, $alt, $dis_in_km, $numOfElem, $poi_lat, $poi_lng) {
        return 3000;
    }

}

$req_dump = print_r($_REQUEST, TRUE);
$fp = fopen('/tmp/request.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
exit;

$lat = $_REQUEST['latitude'];
$lon = $_REQUEST['longitude'];
$alt = $_REQUEST['altitude'];
$dis_in_km = $_REQUEST['radius'];


/*
  $lat=41.799002766525;
  $lon=  12.428941906807;
  $dis_in_km=0.15; //km
 */

JSON_FACTORY::showData($lat, $lon, $dis_in_km, "panoramio");
?>
