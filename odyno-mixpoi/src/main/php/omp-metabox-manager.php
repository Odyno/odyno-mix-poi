<?php

require_once ODYNOMIXPOI_DIR . "/maps/class-omp-post-management.php";

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
    $formData = OMP_Post_Management::get_point_of_post($post->ID);

    $out  = "<label for=\"lng_id\">Longitude</label><input type=\"text\" id=\"lng_id\" name=\"lng\" value=\"" . esc_attr($formData['lng']) . "\" size=\"25\" /><br/>";
    $out .= "<label for=\"lat_id\">Latitude</label><input type=\"text\" id=\"lat_id\" name=\"lat\" value=\"" . esc_attr($formData['lat']) . "\" size=\"25\" />";
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

    set_omp_mixmap_form_data($post_ID, $lng, $lat);

    return $post_id;

}


function set_omp_mixmap_form_data($post_ID, $lng, $lat)
{


    if (isset($lng) AND isset($lat)) {
        //wp_die("CAZZO2");
       OMP_Post_Management::link_to_post_action($post_ID, $lat, $lng);
    } else {
        //wp_die("CAZZO1");
        OMP_Post_Management::unlink_to_post_action($post_ID);
    }
}



?>
