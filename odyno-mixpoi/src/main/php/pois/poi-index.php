<?php

/**
 * This class list all Poi Filtered by current Map
 *
 */

include(ODYNOMIXPOI_DIR . 'maps/class-omp-poi-list.php');
include(ODYNOMIXPOI_DIR . 'maps/class-omp-maps-view.php');





function omp_select_map($var_name, $desciption="",$map_id=null){
    $idUtente = get_current_user_id();
    $listOfMap=OMP_Maps_View::get_maps_list_from_db($idUtente);
    $todo='<label for="omp-'.$var_name.'">'.$desciption.'</label><br>';
    $todo.=' <select name="'.$var_name.'" id="omp-'.$var_name.'">';
    $todo.=' <option id="omp-empty"><label for="omp-empty"></label></option>';
    foreach($listOfMap as $map){
        if ($map["map_id"] == $map_id){
            $sel="selected";
        }
        $todo.='<option value="'.$map["map_id"].'"  id="omp-'.$map["map_id"].'" '.$sel.'><label for="omp-'.$map["map_id"].'">'.$map["name"].'</label></option>';
    }
    $todo.="</select>";
    return $todo;
}


function omp_preview_map($map_id=null){
    if ($map_id == null){
        $out='';
    }   else{
        $out='
    <table><tr><td>
    <div id="omp-maps-preview" >
         <span style="border: 1px solid red; width: 300px; height:300px">Maps</span></div>
    </td><td>
    <div id="omp-maps-descritption">
        <p>Description bla bla bla bla bla blabla bla blabla bla bla</p>
         <ul>
             <li>QrCode</li>
             <li><a href="?page=odyno-mixpoi/maps/poi/poi-mng.php&map_id='.$map_id.'">Add new POI</a></li>
             <li>Import POIs</li>
             <li><a href="?page=odyno-mixpoi/maps/maps-mng.php&action=edit&map_id='.$map_id.'">Rename Map</a></li>
             <li><a href="?page=odyno-mixpoi/maps/maps-mng.php&action=delete&map_id='.$map_id.'">Delete Map</a></li>
         </ul>
    </div>
    </td></tr></table>
    ';
    }


    return $out;
}




if ( isset($_REQUEST['map_id']) && !empty($_REQUEST['map_id'])  ){
    $map_id =   $_REQUEST['map_id'];
    $map_data= OMP_Maps_View::get_maps_from_db($map_id);

    $poiTable = new OMP_Poi_List();
    $pageContent['map-name']=  $map_data[0]['name'];
    $pageContent['map-description']=omp_preview_map($map_id);


}else{
    $map_id =null;
    $pageContent['map-name']= "";
    $pageContent['map-description']="No description for this datasource";


}

$pageContent['map-selector']='<form>'.omp_select_map("map_id","",$map_id).'<input type="hidden" name="page" value="odyno-mixpoi/maps/poi-index.php"><input type="submit" value="Select" class="button-primary" ></form>';





?>

<div class="wrap">

    <div id="icon-users" class="icon32"><br/></div>
    <h2>Manage DataSource </h2>
    <p>In this section you can select your datasource and modify it. </p>
    <h3>Descriptions</h3>
    <?php echo $pageContent['map-description']; ?>
    <h3>Contents</h3>
    <p><?php if (isset($poiTable)){ $poiTable->show("poi-table");}else{ echo " No content for this datasource ";} ?></p>

    <h3>Now is selected <?php $pageContent['map-name']?></h3>
    <?php echo $pageContent['map-selector']; ?>

</div>
