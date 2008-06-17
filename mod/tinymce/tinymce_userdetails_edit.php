<?php

    global $page_owner;

    $visualEditor = __gettext("Visual text editing");
    $visualEditorRules = __gettext("Set this to 'yes' if you would like to use a visual (WYSIWYG) text editor for your posts and comments.");

    $body = <<< END

    <h2>$visualEditor</h2>
    <p>
        $visualEditorRules
    </p>

END;

    $editor = run('userdetails:editor', $page_owner);

    if ($editor == "yes") {
            $body .= templates_draw( array(
                    'context' => 'databox',
                    'name' => __gettext("Enable visual editor: "),
                    'column1' => "<label><input type=\"radio\" name=\"visualeditor\" value=\"yes\" checked=\"checked\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"visualeditor\" value=\"no\" /> " . __gettext("No") . "</label>"
            )
            );
    } else {
            $body .= templates_draw( array(
                    'context' => 'databox',
                    'name' => __gettext("Enable visual editor: "),
                    'column1' => "<label><input type=\"radio\" name=\"visualeditor\" value=\"yes\" /> " . __gettext("Yes") . "</label> <label><input type=\"radio\" name=\"visualeditor\" value=\"no\" checked=\"checked\" /> " . __gettext("No") . "</label>"
            )
            );
    }


    $run_result .= $body;

?>