<?php
/**
 * Sidebar plugin
 * $id$
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

    /**
     * Displays per-block settings on account settings
     */

    global $page_owner;

    $title = __gettext('Sidebar settings');
    $blurb = __gettext("This settings allows you to configure what block you want to display on your sidebar.");

    $body = "<h2>$title</h2>\n<p>$blurb</p>";
    $settings = "<table>";

    $blocks = sidebar_get_blocks();

    foreach ($blocks as $b) {
        $flag_name = 'sidebar'.$b['id'];
        $flag = user_flag_get($flag_name, page_owner());
        if ($flag == 'no') {
            $yescheck = '';
            $nocheck = 'checked="true"';
        } else {
            $yescheck = 'checked="true"';
            $nocheck = '';
        }

        $name = 'flag[' . $flag_name . ']';
        $label = $b['label'];

        $yes = "<label><input type=\"radio\" name=\"$name\" value=\"yes\" $yescheck />".__gettext('Yes')."</label>";
        $no = "<label><input type=\"radio\" name=\"$name\" value=\"no\" $nocheck />".__gettext('No')."</label>";

        $settings .= "<tr><td><strong>$label</strong></td><td>$yes</td><td>$no</td></tr>";
    }
    $settings .= "</table>";

    //FIXME preserve legacy style
    $body .= '<div class="infoholder">' . $settings . '<p></p></div>';

    $run_result .= $body;
?>
