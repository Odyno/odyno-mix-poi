<?php
require_once('../cfg.php');

canGoAhead();

addProbe();

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

$jf = new PanoramioFormatter();

if (isset($_REQUEST['x']) && isset($_REQUEST['y']) && isset($_REQUEST['radius'])) {
    $lat = $_REQUEST['x'];
    $lon = $_REQUEST['y'];
    $dis_in_km = $_REQUEST['radius'];

    $jf->showPixelMeteoNear($lat, $lon, $dis_in_km);
} elseif (isset($_REQUEST['miny']) && isset($_REQUEST['minx']) && isset($_REQUEST['maxy']) && isset($_REQUEST['maxx'])) {

    $minlat = $_REQUEST['miny'];
    $minlng = $_REQUEST['minx'];
    $maxlat = $_REQUEST['maxy'];
    $maxlng = $_REQUEST['maxx'];

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
