<?php

    global $page_owner, $CFG;
        
    // If this is someone else's portfolio, display the user's icon
        if ($page_owner != -1) {
            $run_result .= run("profile:user:info");
        }

    if ((!defined("logged_on") || logged_on == 0) && $page_owner == -1) {

        $body = '<li>';
        $body .= '<form action="'.url.'login/index.php" method="post">';

        if (public_reg == true && ($CFG->maxusers == 0 || (count_users('person') < $CFG->maxusers))) {
            $reg_link = '<a href="' . url . '_invite/register.php">'. __gettext("Register") .'</a> |';
        } else {
            $reg_link = "";
        }

        $basedomain = substr($CFG->wwwroot,0,strlen($CFG->wwwroot) - 1);
        $subdir = str_replace($_SERVER['SERVER_NAME'],"",substr($basedomain,strpos($basedomain,"://") + 3,strlen($basedomain) - 3));
        $passthru = $basedomain . str_replace($subdir,"",$_SERVER['REQUEST_URI']);
        
        $body .= templates_draw(array(
                        'template' => -1,
                        'context' => 'sidebarholder',
                        'title' => __gettext("Log On"),
                        'submenu' => '',
                        'body' => '
            <table>
                <tr>
                    <td align="right"><p>
                        <label>' . __gettext("Username") . '&nbsp;<input type="text" name="username" id="username" style="size: 200px" /></label><br />
                        <label>' . __gettext("Password") . '&nbsp;<input type="password" name="password" id="password" style="size: 200px" />
                        </label>
                        <input type="hidden" name="passthru_url" value="'. $passthru .'" />
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="right"><p>
                        <input type="hidden" name="action" value="log_on" />
                        <label>' . __gettext("Log on") . ':<input type="submit" name="submit" value="'.__gettext("Go").'" /></label><br /><br />
                        <label><input type="checkbox" name="remember" checked="checked" />
                                ' . __gettext("Remember Login") . '</label><br />
                        <small>
                            ' . $reg_link . '
                            <a href="' . url . '_invite/forgotten_password.php">'. __gettext("Forgotten password") .'</a>
                        </small></p>
                    </td>
                </tr>
            
            </table>

'
                    )
                    );
        $body .= "</form></li>";

        $run_result .= $body;
            
    }

?>
