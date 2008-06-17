<?php
// ELGG system configuration parameters.
// You could override default values here, to see all available
// options see lib/config-defaults.php
// Note: some values are override by the values stored in database
// through admin manager

// External URL to the site (eg http://elgg.bogton.edu/)

   $CFG->wwwroot = "http://"; // **MUST** have a final slash at the end

// Database configuration

    $CFG->dbtype = "mysql";
    $CFG->dbhost = "localhost";

    $CFG->dbuser = "";
    $CFG->dbpass = "";

    $CFG->dbname = "";
    $CFG->prefix = "elgg_";

    $CFG->sysadminemail = "";

// Settings for initial administrator, only used at installation time
    $CFG->newsinitialusername = "news";
    $CFG->newsinitialpassword = "";

?>