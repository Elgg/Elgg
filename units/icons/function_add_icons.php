<?php

	// Allow the user to add more icons
		$numicons = db_query("select count(ident) as iconnum from icons where owner = " . $_SESSION['userid']);
		$numicons = $numicons[0]->iconnum;
		
		if ($numicons < $_SESSION['icon_quota']) {

			$body = <<< END
			<p>
				<h2>Upload a new icon</h2>
			</p>
			<p>
				Icons must have maximum dimensions of 100x100 pixels, and may not be larger than 30k in filesize.
				They must be in JPEG, GIF or PNG format.  You may upload up to
				{$_SESSION['icon_quota']} icons in total.
			</p>
			<form action="" method="post" enctype="multipart/form-data">
END;
			$name = "<label for=\"iconfile\">Icon to upload:</label>";
			$column1 = "
						<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"30000\" />
						<input name=\"iconfile\" id=\"iconfile\" type=\"file\" />
						";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$name = "<label for=\"icondescription\">Icon description:</label>";
			$column1 = "<input type=\"text\" name=\"icondescription\" id=\"icondescription\" value=\"\" />";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$name = "<label for=\"icondefault\">Make this your default icon:</label>";
			$column1 = "
							<select name=\"icondefault\" id=\"icondefault\">
								<option value=\"yes\">Yes</option>
								<option value=\"no\">No</option>
							</select>
						";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$body .= <<< END
						<p align="center"><input type="hidden" name="action" value="icons:add" />
							<input type="submit" value="Upload new icon" /></p>
			</form>

END;
		} else {
			$body = <<< END
			<p>
				Your icon quota is {$_SESSION['icon_quota']} and you have {$numicons} icons uploaded.
				You may not upload any more icons until you've deleted some.
			</p>
END;
		}

	$run_result .= $body;
		
?>