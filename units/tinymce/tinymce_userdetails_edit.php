<?php

	$visualEditor = gettext("Visual text editing");
	$visualEditorRules = gettext("Set this to 'yes' if you would like to use a visual (WYSIWYG) text editor for your posts and comments.");

	$body .= <<< END

	<h2>$visualEditor</h2>
	<p>
		$visualEditorRules
	</p>

END;

	$editor = run('userdetails:editor', $page_owner);

	if ($editor == "yes") {
			$body .= run("templates:draw", array(
					'context' => 'databox',
					'name' => gettext("Enable visual editor: "),
					'column1' => "<label><input type=\"radio\" name=\"visualeditor\" value=\"yes\" checked=\"checked\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"visualeditor\" value=\"no\" /> " . gettext("No") . "</label>"
			)
			);
	} else {
			$body .= run("templates:draw", array(
					'context' => 'databox',
					'name' => gettext("Enable visual editor: "),
					'column1' => "<label><input type=\"radio\" name=\"visualeditor\" value=\"yes\" /> " . gettext("Yes") . "</label> <label><input type=\"radio\" name=\"visualeditor\" value=\"no\" checked=\"checked\" /> " . gettext("No") . "</label>"
			)
			);
	}


	$run_result .= $body;

?>
