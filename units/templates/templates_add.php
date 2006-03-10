<?php

	// Create a new template
		$header = gettext("Create theme"); // gettext variable
		$desc = gettext("Here you can create your own themes based on one of the existing public themes. Just select which public theme you would like to alter and then create your own. You will now have edit privilages."); // gettext variable

		$panel = <<< END
		
		<h2>$header</h2>
		<p>$desc</p>
		<form action="index.php" method="post">
		
END;

		$panel .= <<< END
		
END;

		$panel .= run("templates:draw", array(
												'context' => 'databox1',
												'name' => gettext("Theme name"),
												'column1' => run("display:input_field",array("new_template_name","","text"))
											)
											);
		
		$default = gettext("Default Theme"); // gettext variable
		$column1 = <<< END
		
			<select name="template_based_on">
				<option value="-1">$default</option>		
END;
		
		$templates = db_query("select * from templates where owner = " . $_SESSION['userid'] . " or public='yes' order by public");
		if (sizeof($templates) > 0) {
			foreach($templates as $template) {
				$column1 .= "<option value=\"".$template->ident."\">".stripslashes($template->name) . "</option>";
			}
		}
		
		$column1 .= <<< END
			</select>
END;
						
		$panel .= run("templates:draw", array(
												'context' => 'databox1',
												'name' => gettext("Based on"),
												'column1' => $column1
											)
											);
			
		$buttonValue = gettext("Create Theme"); // gettext variable
		$panel .= <<< END
			
			<p>
				<input type="hidden" name="action" value="templates:create" />
				<input type="submit" value="$buttonValue" />
			</p>
		
		</form>
		
END;

		$run_result .= $panel;

?>