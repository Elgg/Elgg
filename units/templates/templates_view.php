<?php

		$user_template = db_query("select template_id from users where ident = " . $_SESSION['userid']);
		$user_template = $user_template[0]->template_id;
              $sitename = sitename;
		$panel = <<< END

	<h2>Select / Create / Edit templates</h2>
	<form action="" method="post">
	<h3>
		Public templates
	</h3>
	<p>
		The following are public templates that you can use to change the way your $sitename looks - these do not change the content only the appearance. Check the preview and then select the one you want. If you wish you can adapt one of these using the 'create template' option below.
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
			$column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template['id']."\" target=\"preview\">Preview</a>";
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
		These are templates that you have created. You can edit and delete these. These template(s) only control actual look and feel - you cannot change any content here. To change any of your content you need to use the other menu options such as: edit profile, update weblog etc.
	</p>
		
END;

			foreach($templates as $template) {				
					$name = "<input type='radio' name='selected_template' value='".$template->ident."' ";
					if ($template->ident == $user_template) {
						$name .= "checked=\"checked\"";
					}
					$name .=" /> ";
					$column1 = "<b>" . stripslashes($template->name) . "</b>";
					$column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template->ident."\" target=\"preview\">Preview</a>";
					$column2 .= " | <a href=\"".url."_templates/edit.php?id=".$template->ident."\" >Edit</a>";
					$column2 .= " | <a href=\"".url."_templates/?action=deletetemplate&delete_template_id=".$template->ident."\" onClick=\"return confirm('Are you sure you want to permanently remove this template?')\">Delete</a>";
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