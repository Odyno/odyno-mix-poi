<?php

/**
 * Questa classe implementa un meccanismo per la gestire del ciclo di vita di un plugin wordpress.
 * Per poter realizzare
 */
if (!class_exists('Ast_Generic_Plugin_Lifecycle_Manager')) :

  /**
   * This class triggers functions that run during activation/deactivation & uninstallation
   */
  abstract class Ast_Generic_Plugin_Lifecycle_Manager {
    const DEVELOPE_MODE = true;

    private static $message = array();

    function __construct($case) {
      if (!$case)
        wp_die('Busted! You should not call this class directly', 'Doing it wrong!');
      
      switch ($case) {
        case 'activate' :
          $this->on_active();
          break;
        case 'deactivate' :
          $this->on_deactive();
          break;

        case 'uninstall' :
          $this->on_unistall();
          break;
        default:
          wp_die("allowed only activate deactivate uninstall");
      }
      
    }

    private function on_active() {

      $this->checkUpdate();
      $this->activate_cb();
   //   update_option($this->get_name().'_version', $this->get_version());

    }

    private function on_deactive() {
      $this->deactivate_cb();
      if (self::DEVELOPE_MODE)
        $this->uninstall_cb();
    }

    private function on_unistall() {
      if (__FILE__ == WP_UNINSTALL_PLUGIN) {
        $this->uninstall_cb();
        delete_option($this->get_name().'_version');
      }
    }

     private function checkUpdate() {
       $version= get_option($this->get_name().'_version','-1');
       if ($version != '-1'){
          if ($this->get_version() == $version){
            $this->update_request_cb($version);
          }
       }else{
         add_option($this->get_name().'_version', $this->get_version());
       }
     }

    function addInfo($message) {
      $this->message[].=$message;
    }

    /**
     * trigger_error()
     *
     * @param (string) $error_msg
     * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
     * @param unknown_type $error_type
     * @return void
     */
    function error($error_msg, $fatal_error = false, $error_type = E_USER_ERROR) {
      if (isset($_GET['action']) && 'error_scrape' == $_GET['action']) {
        echo "{$error_msg}\n";
        if ($fatal_error)
          exit;
      }
      else {
        trigger_error($error_msg, $error_type);
      }
    }

    final public function show_message_cb() {
      echo '<div class="updated"><p>Plugin notice:</p><ol>';
      foreach ($this->message as $note) {
        echo '<li>' . htmlentities($note) . '</li>';
      }
      echo '</ol></div>';
    }

    abstract function get_name();

    abstract function get_version();

    abstract function update_request_cb($installed_version);

    abstract function activate_cb();

    abstract function deactivate_cb();

    abstract function uninstall_cb();
  }

  endif;
?>
