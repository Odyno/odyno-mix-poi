<?php

if (!class_exists('Odynomixpoi_Lifecycle_Handler')) :

    if (!class_exists('Odynomixpoi_db'))
        require_once(ODYNOMIXPOI_DIR . '/install/class-odynomixpoi-db.php');

    if (!class_exists('Ast_Generic_Plugin_Lifecycle_Manager'))
        require_once(ODYNOMIXPOI_DIR . '/install/class-ast-generic-plugin-lifecycle-manager.php');

    class Odynomixpoi_Lifecycle_Handler extends Ast_Generic_Plugin_Lifecycle_Manager
    {

        var $doUpdate = false;
        var $doDataFeel = false;

        function __construct($case)
        {
            parent::__construct($case);
        }

        function get_name()
        {
            return "Odynomixpoi_plugin";
        }

        function get_version()
        {
            return "0.0.1";
        }

        function update_request_cb($installed_version)
        {
            $this->doUpdate = true;
        }

        function activate_cb()
        {
            //Get the table name with the WP database prefix
            $this->addInfo("Do activate procedure");
            $this->_create_update_db_tables();

        }

        function deactivate_cb()
        {
            $this->addInfo("Do deactivate procedure");
        }

        function uninstall_cb()
        {
            $this->addInfo("Do uninstall procedure");
            $this->_drop_db_tables();

        }

        private function _create_update_db_tables()
        {

            global $wpdb;
            $wpdb->show_errors();
            $dbSchema = Odynomixpoi_db::DDLs($wpdb->prefix);
            foreach ($dbSchema as $table_name => $ddl) {
                if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name || $this->doUpdate) {
                    //table no exist or version is no good
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($ddl);
                    $this->doDataFeel = true;

                    $this->addInfo("Table $table_name Update!");
                } else {
                    $this->addInfo("Table $table_name is installed and Updated");
                }
            }

            if ($this->doDataFeel) {
                $dataFill = Odynomixpoi_db::DataFill($wpdb->prefix);
                if ($dataFill != null) {
                    foreach ($dataFill as $table_name => $inserts) {
                        foreach ($inserts as $insertData) {
                            $wpdb->insert($table_name, $insertData);
                        }
                    }
                }
            }

            $wpdb->flush();
            $this->addInfo("DB done");
        }

        private function _drop_db_tables()
        {
            global $wpdb;
            $dbSchema = Odynomixpoi_db::DDLs($wpdb->prefix);
            foreach ($dbSchema as $table_name => $ddl) {
                $wpdb->query("DROP TABLE {$table_name}");
                delete_option($table_name . "_table_ver");
                $this->notice[] .= "Drop $table_name";
            }
            $this->notice[] .= "Droped tables";
        }


    }

endif;
?>
