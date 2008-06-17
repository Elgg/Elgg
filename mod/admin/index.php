<?php

    //    ELGG main admin panel page

    // Run includes
    require_once(dirname(dirname(__FILE__))."/../includes.php");

    define("context", "admin");
    require_login('admin');

    templates_page_setup();

    // controller ;-)
    $do = optional_param('do');
    switch ($do) {
        case'spam':
            $title = __gettext('Spam blocking');
            $body = run('admin:spam');
            break;
        case 'flags':
            $title = __gettext('Content flags');
            $body = run('admin:contentflags');
            break;
        case 'users':
            $title = __gettext('Manage users');
            $view = optional_param('view');
            switch ($view) {
                case 'add':
                    $title .= ' :: ' . __gettext('Add multiple');
                    $body = run('admin:users:add');
                    break;
                case 'admin':
                    $title .= ' :: ' . __gettext('Administrators');
                    $body = run('admin:users:admin');
                    break;
                case 'banned':
                    $title .= ' :: ' . __gettext('Banned');
                    $body = run('admin:users:banned');
                    break;
                default:
                    $body = run('admin:users');
                    break;
            }
            break;
        default:
            $title = __gettext('Administration');
            $body = run('admin:main');
            break;
    }

    // view :-D
    templates_page_output($title, $body);
?>