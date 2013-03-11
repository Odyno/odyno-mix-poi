<?php

/**
 * Description of cass-pixel-meteo-data-provider
 *
 * @author Alessandro Staniscia
 */
class PixelMeteoDataProvider {

    public function getPixelMeteoOnBox($minlatitude, $minlongitude, $maxlatitude, $maxlongitude) {
        $outsID = array();
        
        $p1 = new PointLocation();
        $p1->lat = $minlatitude;
        $p1->long = $minlongitude;

        $p2 = new PointLocation();
        $p2->lat = $maxlatitude;
        $p2->long = $maxlongitude;

        $ArrayOfPoint = array();
        $aa[0] = $p1;
        $aa[1] = $p2;
        
        $outpoints = $p1->getFromBoundingBox($ArrayOfPoint);
         $i = 0;
        foreach ($outpoints as $point) {
            $outsID[$i] = $point->pointid;
            $i++;
        }
        
        return $this->_getData($outsID);
        
    }

    public function getPixelMeteoNear($latitude, $longitude, $dis_in_km) {
        $outsID = array();
        $point = new PointLocation();
        $point->lat = $latitude;
        $point->long = $longitude;

        $p1 = new PointLocation();

        $outpoints = $p1->getPoints($point, $dis_in_km);

        $i = 0;
        foreach ($outpoints as $point) {
            $outsID[$i] = $point[3];
            $i++;
        }
        return $this->_getData($outsID);
    }

    /**
     * Internal function used to extract data from array point ID
     * 
     * @param array pointid $p
     * @param type $dis_in_km
     * @return \Pixel_Meteo
     */
    private function _getData($outpoints) {
        $out = array();
        $p1 = new PointLocation();
        $pointTofind = "";
        foreach ($outpoints as $point) {
            $pointTofind .= "$point,";
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
            $obj = new PixelMeteo();
            $oooo = $p1->Get($bus->pointid);
            $obj->id = $bus->pointid;
            $obj->lat = $oooo->lat;
            $obj->lng = $oooo->long;
            $obj->dsc = self::formatLabel($bus->pointid, $allTempterature, $allRR, $allCld, $lastrptd->date, $lastrptd->time);
            $obj->img_id = self::formatUrl($bus->pointid, $allRR, $allCld);
            $out[$i] = $obj;
            $i++;
        }
        return $out;
    }

    /**
     * Format URL for mixare plugin
     * 
     * @param type $pointid
     * @param type $allRR
     * @param type $allCld
     * @return string
     */
    public static function formatUrl($pointid, $allRR, $allCld) {
        $i = 0;
        //$out="http://192.168.1.129/mondometeo/datashow/arena/images/";
        $out = $GLOBALS['configuration']['root_web'];
        //$out.= '/datashow/arena/images/';
        foreach ($allCld as $cloud) {
            if ($cloud->pointid == $pointid) {
                if ($cloud->value == 0) {
                    //$out.="clearsky.png";
                    $out = '0'; // Clearsky
                } else {
                    $rr = $allRR[$i];
                    if ($rr->value <= 0) { // No Rain
                        //$out.="cirrus.png";
                        $out = '1'; // Cloudy
                    } else if ($rr->value > 0 && $rr->value < 50) {
                        //$out.="cumulonembus.png"; // Rainy
                        $out = '2'; // Rain
                    } else { // thunderstorm
                        //$out.="thunderstorm.png";
                        $out = '3';
                    }
                }
                break;
            }
            $i++;
        }
        return $out;
    }

    /**
     * Formatting one LABEL  from the current inputs
     * 
     * @param type $pointid
     * @param type $allTempterature
     * @param type $allRR
     * @param type $allCld
     * @param type $date
     * @param type $time
     * @return string
     */
    public static function formatLabel($pointid, $allTempterature, $allRR, $allCld, $date, $time) {
        //$i=0;
        foreach ($allTempterature as $obj) {
            if ($obj->pointid == $pointid) {
                //$out="Point ".$i." Temp " .$obj->value." C";
                $out = "Temp N.A.";
                if ($obj->value > -273) {
                    $out = "Temp " . $obj->value . " C";
                }
                continue;
            }
            //$i++;
        }
        foreach ($allRR as $obj) {
            if ($obj->pointid == $pointid) {
                //$out.=$obj->value." mmh";
                $value = "No Rain";
                if ($obj->value > 0 && $obj->value < 50) {
                    $value = "Rainy";
                }
                if ($obj->value >= 50) {
                    $value = "Violent rain";
                }
                $out.=" " . $value;
                continue;
            }
        }
        foreach ($allCld as $obj) {
            if ($obj->pointid == $pointid) {
                $value = "Clear";
                if ($obj->value == 1) {
                    $value = "Cloudy";
                }
                //$out.=" cld-".$obj->value;
                $out.= " " . $value;
                continue;
            }
        }
        $dateOut = date_create_from_format('YmdHi', $date . $time);
        if ($dateOut == false) {
            $dateOutS = $date . $time;
        } else {
            $dateOutS = $dateOut->format('Y/m/d H:i');
        }

        /* $out .= " ". $date;
          $out .= " ". $time; */
        $out .= " " . $dateOutS;
        $out .= " UTC";
        return $out;
    }

}

?>
