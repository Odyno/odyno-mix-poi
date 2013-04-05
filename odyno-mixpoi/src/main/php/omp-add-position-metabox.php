<?php
/*
Copyright 2012  Alessandro Staniscia ( alessandro@staniscia.net )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


require_once ODYNOMIXPOI_DIR . "/pois/class-omp-post-manager.php";

/* Define the custom box */
add_action('add_meta_boxes', 'omp_add_location_box');

/* Do something with the data entered */
add_action('save_post', 'omp_save_location_box');

/* Adds a box to the main column on the Post and Page edit screens */
function omp_add_location_box()
{
    $screens = array('post', 'page');
    foreach ($screens as $screen) {
        add_meta_box(
            'omp_location_box_id',
            'Mixare Poi Location',
            'omp_location_box_html_content',
            $screen,
            "advanced",
            "high"
        );
    }
}

/* Prints the box content */
function omp_location_box_html_content($post)
{
    // Use nonce for verification
    wp_nonce_field(ODYNOMIXPOI__FILE__, 'omp_nonce_location');

    // The actual fields for data entry
    $point = OMP_Post_Manager::get_poi_of_post($post->ID);
    $out ="";
    $out .= "<img src=\"http://qrfree.kaywa.com/?l=1&s=8&d=http%3A%2F%2Fitaca%2Fstaniscia%2Fwp%2Fwp-content%2Fplugins%2Fodyno-mixpoi%2Fshow%2F\" alt=\"QRCode\" width='200px'/><br>";
    $out .= "<a href=\"http://itaca/staniscia/wp/wp-content/plugins/odyno-mixpoi/show/\">testHere</a><br>" ;
    $out .= "<label for=\"lng_id\">Longitude</label><input type=\"text\" id=\"lng_id\" name=\"lng\" value=\"" . esc_attr($point->lng) . "\" size=\"25\" /><br/>";
    $out .= "<label for=\"lat_id\">Latitude</label><input type=\"text\" id=\"lat_id\" name=\"lat\" value=\"" . esc_attr($point->lat) . "\" size=\"25\" />";
    if ($point->id == null ){
        $out .= "<br/><input  type=\"checkbox\" id=\"is_pos_id\" name=\"is_pos\" value=\"true\" checked/><label for=\"is_pos_id\">Disabled</label>";
    }else{
        $out .= "<br/><input  type=\"checkbox\" id=\"is_pos_id\" name=\"is_pos\" value=\"true\" /><label for=\"is_pos_id\">Disabled</label>";
    }
    echo $out;
}

/* When the post is saved, saves our custom data */
function omp_save_location_box($post_id){

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  )
        return $post_id;

    // Secondly we need to check if the user intended to change this value.
    if (
        ! isset( $_REQUEST['omp_nonce_location'] ) ||
        ! wp_verify_nonce( $_REQUEST['omp_nonce_location'] ,  ODYNOMIXPOI__FILE__ ) ){
        return $post_id;
    }

    // Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            echo "current_user_cant edit_page";
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)){
            echo "current_user_cant edit post";
            return $post_id;
        }
    }

    //sanitize user input
    $post_ID=$_REQUEST['post_ID'];
    $lng = sanitize_text_field($_REQUEST['lng']);
    $lat = sanitize_text_field($_REQUEST['lat']);

    OMP_Post_Manager::unlink_to_post_action($post_ID);
    if (!isset($_REQUEST['is_pos'])){
        OMP_Post_Manager::link_to_post_action($post_ID, $lat, $lng);
    }

    return $post_id;
}




?>
