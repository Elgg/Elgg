<?php

    // Sanity checks - conditions under which Elgg will refuse to run
    
    global $CFG;
    
    $diemessages = array();
    
    if ($CFG->dirroot == "") {
        $diemessages[] = 'Configuration problem: The <code>$CFG->dirroot</code> setting in config.php is empty.';
    } elseif (substr($CFG->dirroot, -1) != "/") {
        $diemessages[] = 'Configuration problem: The <code>$CFG->dirroot</code> setting in config.php must end with a forward slash (/).';
    } elseif (!file_exists($CFG->dirroot)) {
        //this needs checking now, because includes.php needs it to work
        $diemessages[] = 'Configuration problem: The <code>$CFG->dirroot</code> setting in config.php points to a directory that does not exist.';
    } elseif (!is_dir($CFG->dirroot)) {
        $diemessages[] = 'Configuration problem: The <code>$CFG->dirroot</code> setting in config.php points to a location that is not a directory.';
    }
    
    if (!preg_match('#^https?://.+#', $CFG->wwwroot)) {
        $diemessages[] = 'Configuration problem: The <code>$CFG->wwwroot</code> setting in config.php is empty or not a valid URL.';
    } elseif (substr($CFG->wwwroot, -1) != "/") {
        $diemessages[] = 'Configuration problem: The <code>$CFG->wwwroot</code> setting in config.php must end with a forward slash (/).';
    }
    
    if ($CFG->dataroot == "") {
        $diemessages[] = 'Configuration problem: The <code>$CFG->dataroot</code> setting in config.php is empty.';
    } elseif (substr($CFG->dataroot, -1) != "/") {
        $diemessages[] = 'Configuration problem: The <code>$CFG->dataroot</code> setting in config.php must end with a forward slash (/).';
    }
    
    if (ini_get('register_globals')) {
        // this shouldn't be needed due to the htaccess file, but just in case...
        $diemessages[] = "
            Configuration problem: The PHP setting 'register_globals', which is a huge security risk, is turned on.
            There should be a line in the .htaccess file as follows: <code>php_flag register_globals off</code>
            If the line is present but has a # at the start, remove the # character.
        ";
    }
    
    
    switch ($CFG->dbtype) {
        case 'mysql':
            $funcheck = 'mysql_query';
            break;
        case 'postgres7':
            $funcheck = 'pg_query';
            break;
    }
    if (!function_exists($funcheck)) {
        // people have been having a spot of trouble installing elgg without the mysql php module...
        $diemessages[] = "
            Installation problem: Can't find the PHP MySQL or Postgresql module.
            Even with PHP and MySQL or Postgresql installed, sometimes the module to connect them is missing.
            Please check your PHP installation.
        ";
    }
    
    
    if (count($diemessages)) {
        $diebody  = '<html><body><h1>Elgg isn\'t ready to run. :(</h1><ul>';
        $diebody .= '<li>' . implode("</li><li>", $diemessages) . '</li>';
        $diebody .= '</ul><p>Please read the INSTALL and config-dist.php files for more information,';
        $diebody .= '<a href="_elggadmin/">or click here to use the friendly installer</a>.</p>';
        $diebody .= '</body></html>';
        die($diebody);
    } else {
        unset($diemessages);
    }
    
    
    
    
?>