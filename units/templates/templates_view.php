<?php

		$user_template = db_query("select template_id from users where ident = " . $_SESSION['userid']);
		$user_template = $user_template[0]->template_id;

		$panel = <<< END

	<h2>Create / Edit / Delete templates</h2>
	<form action="" method="post">
	<h3>
		Public templates
	</h3>
	<p>
		The following are templates that have been made available to you. You may preview and select them, but not edit and delete.
	</p>
	
END;

		$template_list[] = array(
									'name' => 'Default Template',
									'id' => -1
								);
		$templates = db_query("select * from templates where public = 'yes'");
		if (sizeof($templates) > 0) {
			foreach($templates as $template) {
				$template_list[] = array(
											'name' => stripslashes($template->name),
											'id' => stripslashes($template->ident)
										);
			}
		}
		foreach($template_list as $template) {
			$name = "<input type='radio' name='selected_template' value='".$template['id']."' ";
			if ($template['id'] == $user_template) {
				$name .= "checked=\"checked\"";
			}
			$name .=" /> ";
			$column1 = "<b>" . $template['name'] . "</b>";
			$column2 = "<a href=\"/_templates/preview.php?template_preview=".$template['id']."\" target=\"preview\">Preview</a>";
			$panel .= run("templates:draw", array(
														'context' => 'databox',
														'name' => $name,
														'column1' => $column1,
														'column2' => $column2
													)
													);
		}

		$templates = db_query("select * from templates where owner = " . $_SESSION['userid']);
		if (sizeof($templates) > 0) {
			$panel .= <<< END
	<h3>
		Personal templates
	</h3>
	<p>
		These are templates that you have created.
	</p>
		
END;

			foreach($templates as $template) {				
					$name = "<input type='radio' name='selected_template' value='".$template->ident."' ";
					if ($template->ident == $user_template) {
						$name .= "checked=\"checked\"";
					}
					$name .=" /> ";
					$column1 = "<b>" . stripslashes($template->name) . "</b>";
					$column2 = "<a href=\"/_templates/preview.php?template_preview=".$template->ident."\" target=\"preview\">Preview</a>";
					$column2 .= " | <a href=\"/_templates/edit.php?id=".$template->ident."\" >Edit</a>";
					$column2 .= " | <a href=\"/_templates/?action=deletetemplate&delete_template_id=".$template->ident."\" onClick=\"return confirm('Are you sure you want to permanently remove this template?')\">Delete</a>";
					$panel .= run("templates:draw", array(
														'context' => 'databox',
														'name' => $name,
														'column1' => $column1,
														'column2' => $column2
													)
													);
			}
		}

	$panel .= <<< END
	
		<p>
			<input type="submit" value="Select new template" />
			<input type="hidden" name="action" value="templates:select" />
		</p>
		
	</form>
	
END;

	$run_result .= $panel;
			
?>