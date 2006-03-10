<?php

	global $template;
	global $template_definition;
	
	if (!isset($parameter)) {
	// Get template details
		$template_id = db_query("select template_id from users where ident = " . $_SESSION['userid']);
		if (sizeof($template_id) > 0) {
			$template_id = $template_id[0]->template_id;
		} else {
			$template_id = -1;
		}
	} else {
		if (!is_array($parameter)) {
			$template_id = (int) $parameter;
		} else {
			$template_id = -1;
		}
	}

	// Grab title, see if we can edit the template
		$editable = 0;
		if ($template_id == -1) {
			$templatetitle = gettext("Default Theme");
		} else {
			$templatestuff = db_query("select * from templates where ident = $template_id");
			$templatetitle = stripslashes($templatestuff[0]->name);
			if ($templatestuff[0]->owner == $_SESSION['userid']) {
				$editable = 1;
			}
			if (($templatestuff[0]->owner != $_SESSION['userid']) && ($templatestuff[0]->public != 'yes')) {
				$template_id = -1;
			}
		}
	
	// Grab the template content
		if ($template_id == -1) {
			$current_template = $template;
		} else {
			$result = db_query("select * from template_elements where template_id = $template_id");
			if (sizeof($result) > 0) {
				foreach($result as $element) {
					$current_template[stripslashes($element->name)] = stripslashes($element->content);
				}
			} else {
				$current_template = $template;
			}
		}
	
	$run_result .= <<< END
	
	<form action="" method="post">
	
END;
	
	$run_result .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => gettext("Theme Name"),
												'contents' => run("display:input_field",array("templatetitle",$templatetitle,"text"))
											)
											);

	foreach($template_definition as $element) {
		
		if (isset($element['display']) && $element['display'] == 1) {
		
			$name = "<b>" . $element['name'] . "</b><br /><i>" . $element['description'] . "</i>";
			$glossary = gettext("Glossary"); // gettext variable
	
			if (sizeof($element['glossary']) > 0) {
				$column1 = "<b>$glossary</b><br />";
				foreach($element['glossary'] as $gloss_id => $gloss_descr) {
					$column1 .= $gloss_id . " -- " . $gloss_descr . "<br />";
				}
			} else {
				$column1 = "";
			}
			
			if ($current_template[$element['id']] == "" || !isset($current_template[$element['id']])) {
				$current_template[$element['id']] = $template[$element['id']];
			}
			
			$column2 = run("display:input_field",array("template[" . $element['id'] . "]",$current_template[$element['id']],"longtext"));
	/*		
			$run_result .= run("templates:draw", array(
									'context' => 'databox',
									'name' => $name,
									'column2' => $column1,
									'column1' => $column2
								)
								);
	*/
			$run_result .= run("templates:draw", array(
									'context' => 'databoxvertical',
									'name' => $name,
									'contents' => $column1 . "<br />" . $column2
								)
								);

		}									
	}
	
	if ($editable) {
		$save = gettext("Save"); // gettext variable
		$run_result .= <<< END
	
		<p align="center">
			<input type="hidden" name="action" value="templates:save" />
			<input type="hidden" name="save_template_id" value="$template_id" />
			<input type="submit" value="$save" />
		</p>	
	
END;
	} else {
		$noEdit = gettext("You may not edit this theme. To create a new, editable theme based on the default, go to <a href=\"index.php\">the main themes page</a>."); // gettext variable
		$run_result .= <<< END
		
		<p>
			$noEdit
		</p>
		
END;
	}
	$run_result .= <<< END
		
	</form>
	
END;

?>