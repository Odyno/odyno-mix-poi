<?php
// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
  echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
  exit;
}



require_once ODYNOMIXPOI_DIR."/maps/class-omp-maps-view.php";

function get_map_form_data($map_id=null,$action=null) {


  $dbMapData = ($map_id != null) ? OMP_Maps_View::get_maps_from_db($map_id) : null;

  if (!isset($dbMapData[0])) {
    $out['map_id'] = "";
    $out['name'] = "";
//    $out['action']

  } else {
    $out['map_id'] = $map_id;
    $out['name'] = $dbMapData[0]['name'];
//    $out['action']

  }
  
  $out['action'] = ($action != null) ? $action : 'insert';
  $out['map_id']= ($map_id != null) ? $map_id : null;

  return $out;
}

$form_data = get_map_form_data(@$_REQUEST['map_id'], @$_REQUEST['action']);
?>






<div class="wrap">

    <div class="icon32"><img src="<?php echo ODYNOMIXPOI_URL.'/res/icon_datasource32x32.png'; ?>"></div>
    <h2>Maps</h2>

    <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        <p>This page allow you to create o modify Maps </p>
    </div>



  <form action="?page=/odyno-mixpoi/maps/maps-index.php" method="post">

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