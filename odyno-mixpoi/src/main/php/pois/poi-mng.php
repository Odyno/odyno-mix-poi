<?php
// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
  echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
  exit;
}



require_once ODYNOMIXPOI_DIR . "/maps/class-omp-poi-view.php";

function get_poi_form_data($poi_id=null, $map_id=null,$action=null) {


  $dbMapData = ($poi_id != null) ? OMP_Poi_View::get_poi_from_db($poi_id) : null;

  if (!isset($dbMapData[0])) {
    $out['title'] = "";
    $out['url'] ="";
      $out['lat'] ="";
      $out['lng'] ="";
      $out['elev'] ="";
//    $out['action']

  } else {
      $out['title'] = $dbMapData[0]['title'];
      $out['url'] =$dbMapData[0]['url'];
      $out['lat'] =$dbMapData[0]['lat'];
      $out['lng'] =$dbMapData[0]['lng'];
      $out['elev'] =$dbMapData[0]['elev'];
//    $out['action']

  }
  
  $out['action'] = ($action != null) ? $action : 'insert';
  $out['map_id']= ($map_id != null) ? $map_id : null;
  $out['poi_id']= ($poi_id != null) ? $poi_id : null;

  return $out;
}

$form_data = get_poi_form_data(@$_REQUEST['poi_id'], @$_REQUEST['map_id'], @$_REQUEST['action']);
?>






<div class="wrap">

    <div class="icon32"><img src="<?php echo ODYNOMIXPOI_URL.'/res/icon_datasource32x32.png'; ?>"></div>
    <h2>Maps</h2>

    <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        <p>This page allow you to create o modify Maps </p>
        <pre><?php print_r($form_data);?></pre>
    </div>



  <form action="?page=/odyno-mixpoi/maps/poi/poi-index.php" method="post">


    <h3>Compila i seguenti campi</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th><label for="name">Name Of DataSource</label></th>
          <td><input type="text" value="<?php echo $form_data['name']; ?>" tabindex="1" id="name" name="name" size="40  ">
            <div class="description">name of datasource</div></td>
        </tr>
      </tbody>
    </table>


    <p class="submit">
      <input type="hidden" name="do" value="<?php echo $form_data['action']; ?>">
      <input type="hidden" name="map_id" value="<?php echo $form_data['map_id']; ?>">
      <input type="reset" class="button" value="Reset">
      <input type="submit" value="<?php echo $form_data['action']; ?>" class="button-primary" tabindex="2" accesskey="p" >
      <br class="clear">
    </p>

  </form>

</div>