<?php
 //add Filter
    add_filter('the_content', 'checkPageSpaceHoldForMixarePoi');
    
if (!function_exists("checkPageSpaceHoldForMixarePoi")) {
    
   

    //do Filter
    function checkPageSpaceHoldForMixarePoi($content) {

//        $internalRedirectHappens = "";
//        
//        if (isset($_SERVER['REDIRECT_URL'])) {
//            $internalRedirectHappens = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
//        }
        
        if ($GLOBALS['post']->post_content == '[mixare-poi]' && 
            get_option('mixare-poi-path') ==  $internalRedirectHappens) {
            $p = "";
            if (!isset($_GET['page'])) {
                if (!is_user_logged_in()) {
                    //get the login template
                    include_once(MIXAREPOI_DIR.'/filter/templates/login-form.tmpl.php');
                } else {
                    //get the profile template
                    include_once(MIXAREPOI_DIR.'/maps/available.php');
                }
                $p = null;
            } else {
                $p = $_GET['page'];
            }

            //check all pages
            if ($p == 'Profile') {
                include_once('templates/profile-form.tmpl.php');
            } else if ($p == 'Register') {
                include_once('templates/register-form.tmpl.php');
            } else if ($p == 'Password') {
                include_once('templates/password-form.tmpl.php');
            } else if ($p == 'Maps') {
                include_once('wp-content/plugins/mixare-poi/maps/available.php');
            }
        } else if (
                $GLOBALS['post']->post_content == '[mixare-poi]' && 
                get_option('mixare-poi-map-path') == $internalRedirectHappens) {
            include_once(MIXAREPOI_DIR.'/maps/output/all.php');
        } else {
            return $content;
        }
    }

}
?>
