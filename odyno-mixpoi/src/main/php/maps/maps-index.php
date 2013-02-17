<?php

/**
 * This class list All Maps Filtered by current User
 */
include(  ODYNOMIXPOI_DIR . '/maps/class-omp-maps-list.php');
include(  ODYNOMIXPOI_DIR . '/maps/class-omp-maps-manager.php');


$mapsTable = new OMP_Maps_List();
$mapsManager=new OMP_Maps_Manager();
$mapsManager->applayAction($_REQUEST);

?>


<div class="wrap">

  <div id="icon-users" class="icon32"><br/></div>
  <h2>Maps</h2>

  <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
    <p>This is a list of your maps</p>
  </div>

  <?php $mapsTable->show("fuel-table") ?>

</div>
