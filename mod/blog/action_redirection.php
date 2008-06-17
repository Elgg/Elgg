<?php

    //    ELGG weblog perform-action-then-redirect page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

        run("weblogs:init");

        header_redirect((defined('redirect_url') ? redirect_url : url));

?>