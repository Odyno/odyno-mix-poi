<?php

require_once('../cfg.php');
addProbe();

/*
  $req_dump = print_r($_REQUEST, TRUE);
  $fp = fopen('/tmp/request.log', 'a');
  fwrite($fp, $req_dump);
  fclose($fp);
  exit;
 */

$jf = new Formatter ();


if (isset($_REQUEST['lat']) && isset($_REQUEST['lng']) && isset($_REQUEST['radius'])) {
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lng'];
    $dis_in_km = $_REQUEST['radius'];

    /*
      $lat=41.799002766525;
      $lon=  12.428941906807;
      $dis_in_km=80; //km
     * 
     */


    $jf->showPixelMeteoNear($lat, $lon, $dis_in_km);
} elseif (isset($_REQUEST['minlat']) && isset($_REQUEST['minlng']) && isset($_REQUEST['maxlat']) && isset($_REQUEST['maxlng'])) {

    $minlat = $_REQUEST['minlat'];
    $minlng = $_REQUEST['minlng'];
    $maxlat = $_REQUEST['maxlat'];
    $maxlng = $_REQUEST['maxlng'];

    $jf->showPixelMeteoOnBox($minlat, $minlng, $maxlat, $maxlng);
} else {

////41.677015,12.858673
////        42.102298,12.10611
//    
//    
//    $minlat = '41.677015';
//    $minlng = '12.106110';
//    $maxlat = '42.102298';
//    $maxlng = '12.858673';
//
//    $jf = new Formatter ();
//    $jf->showPixelMeteoOnBox($minlat, $minlng, $maxlat, $maxlng);
//    

    header("HTTP/1.0 404 Not Found");
    exit;
}
?>
