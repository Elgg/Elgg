<?php

    // error_reporting(E_ERROR | E_WARNING | E_PARSE);

    // All installation specific parameters should be in a file 
    // that is not part of the standard distribution.
        //TODO: disabled this message, go directly to installer
        if (false && !file_exists(dirname(__FILE__)."/config.php")) {
            $message = <<< END
<html>
<head>
    <title>Elgg installation</title>
</head>
<body>
    <h1>Elgg isn't ready to run just yet.</h1>
    <p>
        There is not a whole lot of work to do to get up and running, but
        there is a bit. Here's what you have to do:
    </p>
    <ol>
        <li>Read the INSTALL file that came with your installation package.
        <li><a href="_elggadmin/">Click here to use the visual installer</a>.</li>
    </ol>
    <p>
        If you have any problems, head over to the main Elgg site
        at <a href="http://elgg.org/">Elgg.org</a>.
    </p>
</body>
</html>
END;
            die($message);
        }

    // Default config values
        require_once(dirname(__FILE__).'/lib/config-defaults.php');
    // override config values
        if (is_readable(dirname(__FILE__).'/config.php')) {
            require_once(dirname(__FILE__)."/config.php");
        }

    // Check if should go to install
        if (empty($CFG->dbname) || empty($CFG->dbuser)) {
            header('Location: install.php');
            exit();
        }

    // Check for .htaccess
        if (!file_exists(dirname(__FILE__)."/.htaccess")) {
            $message = <<< END
<html>
<head>
    <title>Elgg installation</title>
</head>
<body>
    <h1>Elgg still is not ready to run just yet. (Sorry.)</h1>
    <p>
        You're going to need to rename the <i>htaccess-dist</i> file that
        came with your installation (it's in the main installation directory)
        to <i>.htaccess</i>.
    </p>
    <p>
        If you're using a Windows <i>server</i>, this is a bit of a problem.
        Windows doesn't like files starting in a period,
        but there's a workaround: open htaccess-dist in Notepad, click Save As,
        change the file type pulldown to all files (*.*), and type .htaccess
        in the filename box.
    </p>
    <p>
        If you're using any other kind of server, all is well with the world.
        (If you're uncertain, try renaming the file: most servers run on Linux
        or a similar operating system.)
    </p>
    <p>
        Read the INSTALL file that came with your installation for more information
        about installing Elgg.
    </p>
</body>
</html>
END;
            die($message);
        }

    // Check config values make sense
        require_once(dirname(__FILE__).'/lib/sanitychecks.php');

    /***************************************************************************
    *    HELPER LIBRARIES
    ****************************************************************************/

    // Load cache lib
        require_once($CFG->dirroot.'lib/cache/lib.php');

    // Load datalib
        require_once($CFG->dirroot.'lib/datalib.php');

    // Load elgglib
        require_once($CFG->dirroot.'lib/elgglib.php');

    // Load constants
        require_once($CFG->dirroot.'lib/constants.php');

    /***************************************************************************
    *    CORE FUNCTIONALITY LIBRARIES
    ****************************************************************************/

    
    // Load setup.php which will initialize database connections and such like.
        require_once($CFG->dirroot.'lib/setup.php');

    // Language / internationalisation
    //@todo All the libraries has a strong dependence with this 'plugin'
        require_once($CFG->dirroot . "mod/gettext/lib.php");


    // Plug-in engine (must be loaded first)
        require($CFG->dirroot . "lib/engine.php");

    // XML parsing
        require($CFG->dirroot . "lib/xmllib.php");

    // User functions
        require_once($CFG->dirroot.'lib/userlib.php');

    // Check database
        require_once($CFG->dirroot.'lib/dbsetup.php');

    /***************************************************************************
    *    START-OF-PAGE RUNNING
    ****************************************************************************/

        if ($allmods = get_list_of_plugins('mod') ) {
            foreach ($allmods as $mod) {
                $mod_init = $mod . '_init';
                if (function_exists($mod_init)) {
                    $mod_init();
                   }
           }
        }

        run("init");

    // Walled garden checking: if we're not logged in,
    // and walled garden functionality is turned on, redirect to
    // the logon screen
        if (!empty($CFG->walledgarden) && (!defined("context") || context != 'external') && !logged_on) {
            require_login();
        }

?>