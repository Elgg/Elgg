<?php
global $db, $METATABLES;

/// Check if the main tables have been installed yet or not.
if (!$METATABLES) {    // No tables yet at all.
    $maintables = false;
} else {
    $maintables = false;
    $datalists = false;
    foreach ($METATABLES as $table) {
        if (preg_match("/^{$CFG->prefix}users$/", $table)) {
            $maintables = true;
        }
        if (preg_match("/^{$CFG->prefix}datalists$/", $table)) {
            $datalists = true;
        }
    }
}

$strdatabasesuccess = "Yay!"; // well, if people leave never-defined variables about the place...

$newinstall = false;

if (!$maintables) {
    if (file_exists($CFG->dirroot . "lib/db/$CFG->dbtype.sql")) {
        $db->debug = true;
        
        //version check
        $continue = true;
        $infoarr = $db->ServerInfo();
        if (!empty($infoarr['version'])) {
            switch($CFG->dbtype) {
                case "mysql":
                    if (!preg_match('/^(4\.1|[5-9]\.|[0-9][0-9]+)/', $infoarr['version'])) {
                        error('Error: Your MySQL version is too old: ' . $infoarr['version'] . '. Elgg requires MySQL 4.1 or newer. 5.0 or newer is recommended.');
                        $continue = false;
                    }
                break;
            }
        }
        
        if ($continue) {
            if (modify_database($CFG->dirroot . "lib/db/$CFG->dbtype.sql")) {
                include_once($CFG->dirroot . "version.php");
                set_config('version', $version);
                $db->debug = false;
                notify($strdatabasesuccess, "green");
                if (!isset($CFG->newsinitialpassword) || empty($CFG->newsinitialpassword)) {
                    notify("WARNING: the initial password for the news account is 'password'. This account has administrator privileges, and you should log in and change the password as soon as installation is complete.");
                } else {
                    //$newspassword = $db->qstr(md5($CFG->newsinitialpassword));
                    //execute_sql("update ".$CFG->prefix."users set password = $newspassword where username = 'news'");
                    set_field('users', 'password', md5($CFG->newsinitialpassword), 'username', 'news');
                }
                //execute_sql("update ".$CFG->prefix."users set email = ". $db->qstr($CFG->sysadminemail) ." where username = 'news'");
                set_field('users', 'email', $CFG->sysadminemail, 'username', 'news');
                // store sysadminemail in db
                set_config('sysadminemail', $CFG->sysadminemail);

                // change initial administrator if it's set
                /*
                if (!empty($CFG->newsinitialusername)) {
                    set_field('users', 'name', 'Administrator', 'username', 'news');
                    set_field('users', 'username', $CFG->newsinitialusername, 'username', 'news');
                }
                 */

            } else {
                $db->debug = false;
                error("Error: Main databases NOT set up successfully");
            }
        }
    } else {
        error("Error: Your database ($CFG->dbtype) is not yet fully supported by Elgg.  See the lib/db directory.");
    }
    print_continue("index.php");
    die;
}

if (user_flag_get("admin",$_SESSION['userid'])) {
    
    if (empty($CFG->version)) {
        $CFG->version = 1;
    }

    if (empty($CFG->release)) {
        $CFG->release = "";
    }

    if (!$datalists) {
        $CFG->version = -1;
    }

    /// Upgrades
    include_once($CFG->dirroot . "version.php");              # defines $version
    include_once($CFG->dirroot . "lib/db/$CFG->dbtype.php");  # defines upgrades

    if ($CFG->version) {
        if ($version > $CFG->version) {  // upgrade

            $a->oldversion = "$CFG->release ($CFG->version)";
            $a->newversion = "$release ($version)";

            if (empty($_GET['confirmupgrade'])) {
                notice_yesno(__gettext('Need to upgrade database'), $CFG->wwwroot . '?confirmupgrade=yes', '');
                exit;

            } else {
                $db->debug=true;
                if (main_upgrade($CFG->version)) {
                    $db->debug=false;
                    if (set_config("version", $version)) {
                        notify($strdatabasesuccess, "green");
                        print_continue("index.php");
                        exit;
                    } else {
                        notify("Upgrade failed!  (Could not update version in config table)");
                    }
                } else {
                    $db->debug=false;
                    notify("Upgrade failed!  See /version.php");
                }
            }
        } else if ($version < $CFG->version) {
            notify("WARNING!!!  The code you are using is OLDER than the version that made these databases!");
        }

    } else {
        if (set_config("version", $version)) {
            print_header("Elgg $release ($version)");
            print_continue("index.php");
            die;
        } else {
            $db->debug=true;
            if (main_upgrade(0)) {
                print_continue("index.php");
            } else {
                error("A problem occurred inserting current version into databases");
            }
            $db->debug=false;
        }
    }

}
?>