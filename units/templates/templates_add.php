<?php

	// Create a new template
	
		$panel = <<< END
		
		<h2>Create template</h2>
		<form action="index.php" method="post">
		
END;

		$panel .= <<< END
		
END;

		$panel .= run("templates:draw", array(
												'context' => 'databox1',
												'name' => 'Template name',
												'column1' => run("display:input_field",array("new_template_name","","text"))
											)
											);
		
		$column1 = <<< END
		
			<select name="template_based_on">
				<option value="-1">Default Template</option>		
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
												'name' => 'Based on',
												'column1' => $column1
											)
											);
			
		$panel .= <<< END
			
			<p>
				<input type="hidden" name="action" value="templates:create" />
				<input type="submit" value="Create Template" />
			</p>
		
		</form>
		
END;

		$run_result .= $panel;

?>