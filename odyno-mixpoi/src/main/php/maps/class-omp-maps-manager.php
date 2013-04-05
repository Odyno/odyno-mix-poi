<?php

/**
 * Manage All action of Maps
 */
if (!class_exists('OMP_Maps_Manager')) {

    class OMP_Maps_Manager
    {

        var $wpdb, $databasePre;

        function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->databasePre = $wpdb->prefix . "omp_";

        }

        function applayAction($commands)
        {
            try {
                if (isset($commands['do'])) {
                    $do = @$commands['do'] . "_action";
                    $fnc = new ReflectionMethod('OMP_Maps_Manager', $do);
                    $fnc->invoke($this, $commands);
                }
            } catch (Exception $e) {
                if (WP_DEBUG) {
                    wp_die("Action not Allowed!:" . $e->__toString());
                } else {
                    wp_die("Action not Allowed!");
                }
            }
        }

        function delete_action($commands)
        {
            // print_r($commands);
            $table = $this->databasePre . "map";
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM $table WHERE map_id = %s", $commands['map_id']));
        }

        function edit_action($commands)
        {
            // print_r($commands);
            $table = $this->databasePre . "map";
            $data = array('name' => $commands['name']);
            $where = array('map_id' => $commands['map_id']);
            $format = '%s';
            $this->wpdb->update($table, $data, $where);
        }

        function insert_action($commands)
        {
            $rows_affected = $this->wpdb->insert($this->databasePre . "map", array('name' => $commands['name'], 'map_id' => @$commands['map_id'], 'utente_id' => get_current_user_id()));
        }

        static function assoc($poi_id, $map_id)
        {

        }

        static function deassoc($poi_id, $map_id)
        {

        }


    }

}
?>
