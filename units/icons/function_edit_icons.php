<?php

	// Display icons and allow user to edit their names or delete some
	
	// Get all icons associated with a user
		$icons = db_query("select * from icons where owner = " . $_SESSION['userid']);

		$body = <<< END
		<h2>
			Your Icons
		</h2>
END;
		
	// If we have some icons, display them; otherwise explain that there isn't anything to edit
		if (sizeof($icons) > 0) {
			
			$body .= <<< END
		<form action="" method="post" />		
			<p>
				Your current icons are listed below.
			</p>
END;
			foreach($icons as $icon) {
				list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon->filename);

				$name = <<< END
						<label>Delete:
							<input type="checkbox" name="icons_delete[]" value="{$icon->ident}" />
						</label>
END;
				$column1 = <<< END
						<p align="center"><img src="/_icons/data/{$icon->filename}" {$attr} /></p>
END;
				if ($icon->filename == $_SESSION['icon']) {
					$checked = "checked=\"checked\"";
				} else {
					$checked = "";
				}
				$defaulticon = htmlentities(stripslashes($icon->description));
				$column2 = <<< END
						<label>Name:
							<input	type="text" name="description[{$icon->ident}]" 
									value="{$defaulticon}" />
						</label><br />
						<label>Default: <input type="radio" name="defaulticon" value="{$icon->ident}" {$checked} /></label>
END;

				$body .= run("templates:draw", array(
								'context' => 'databox',
								'name' => $column1,
								'column1' => $column2,
								'column2' => $name
							)
							);

			}
			
			if ($_SESSION['icon'] == "default.png") {
				$checked = "checked = \"checked\"";
			} else {
				$checked = "";
			}
			$column1 = <<< END
						<label>No default:
						<input type="radio" name="defaulticon" value="-1" {$checked} /></label>
END;
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'column1' => $column1
						)
						);
			$body .= <<< END
				<p align="center">
					<input type="hidden" name="action" value="icons:edit" />
					<input type="submit" value="Save" />		
				</p>
			</form>
END;
			
		} else {

	$body .= <<< END
		<p>
			You don't have any icons loaded yet.
		</p>
END;

		}

		$run_result .= $body;
?>