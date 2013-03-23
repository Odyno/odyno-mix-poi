<?php

require_once  '../../../wp-config.php';

define('ODYNOMIXPOI_SHOW_DIR', ABSPATH . PLUGINDIR . '/odyno-mixpoi/show/');

/*
  $req_dump = print_r($_REQUEST, TRUE);
  $fp = fopen('/tmp/request.log', 'a');
  fwrite($fp, $req_dump);
  fclose($fp);
  exit;
$lat = 41.799002766525;
$lon = 12.428941906807;
$dis_in_km = 80; //km
*/

if (isset($_REQUEST['render']) && $_REQUEST['render'] == "1") {
    /*
    esempio invio panoramio

    Array
    (
        [set] => public
        [from] => 0
        [to] => 30
        [minx] => 11.746202
        [miny] => 41.159588
        [maxx] => 13.346202
        [maxy] => 42.75959
        [size] => thumbnail
        [mapfilter] => true

         url "?set=public&from=0&to=30&minx=11.746202&miny=41.159588&maxx=13.346202&maxy=42.75959&size=thumbnail&mapfilter=true"

    )
    */

    require_once(ODYNOMIXPOI_SHOW_DIR . '/class-panoramio-formatter.php');
    $jf = new  Panoramio_Formatter();
} else {


    require_once(ODYNOMIXPOI_SHOW_DIR . '/class-mixare-formatter.php');
    $jf = new  Mixare_Formatter();
}


if (isset($_REQUEST['latitude']) && isset($_REQUEST['longitude']) && isset($_REQUEST['radius'])) {
    $lat = $_REQUEST['latitude'];
    $lon = $_REQUEST['longitude'];
    $dis_in_km = $_REQUEST['radius'];

    $jf->showPixelsNear($lat, $lon, $dis_in_km);
} elseif (isset($_REQUEST['minlat']) && isset($_REQUEST['minlng']) && isset($_REQUEST['maxlat']) && isset($_REQUEST['maxlng'])) {

    $minlat = $_REQUEST['minlat'];
    $minlng = $_REQUEST['minlng'];
    $maxlat = $_REQUEST['maxlat'];
    $maxlng = $_REQUEST['maxlng'];

    $jf->showPixelsOnBox($minlat, $minlng, $maxlat, $maxlng);
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
