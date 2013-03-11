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
            $screen
        );
    }
}

/* Prints the box content */
function omp_location_box_html_content($post)
{
    // Use nonce for verification
    wp_nonce_field(ODYNOMIXPOI__FILE__, 'omp_noncename');
    // The actual fields for data entry
    if (isset($_POST['post_id'])){
    $formData = OMP_Post_Management::get_point_of_post($_POST['post_id']);
    }

    $out = "<label for=\"lng_id\">Longitude</label><input type=\"text\" id=\"lng_id\" name=\"lng\" value=\"" . esc_attr(@$formData['lng']) . "\" size=\"25\" />";
    $out .= "<label for=\"lat_id\">Latitude</label><input type=\"text\" id=\"lat_id\" name=\"lat\" value=\"" . esc_attr(@$formData['lat']) . "\" size=\"25\" />";
    echo $out;
     echo  "<pre>". phpinfo() ."</pre>";
}

/* When the post is saved, saves our custom data */
function omp_save_location_box($post_id)
{
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if (!isset($_POST['omp_noncename']) || !wp_verify_nonce($_POST['omp_noncename'], plugin_basename(ODYNOMIXPOI__FILE__)))
        return;


    // Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    // OK, we're authenticated: we need to find and save the data
    //if saving in a custom table, get post_ID
    $post_ID = $_POST['post_id'];

    //sanitize user input
    $lng = sanitize_text_field($_POST['lng']);
    $lat = sanitize_text_field($_POST['lat']);

    set_omp_mixmap_form_data($post_ID, $lng, $lat);

}


function set_omp_mixmap_form_data($post_ID, $lng, $lat)
{

    if (isset($lng) AND isset($lat) and $lat != "0.0" and  $lng != "0.0") {

        OMP_Post_Management::link_to_post_action($post_ID, $lat, $lng);
    } else {

        OMP_Post_Management::unlink_to_post_action($post_ID);

    }
}

?>
