<?php

	// Display icons and allow user to edit their names or delete some
	
	global $page_owner;

	$url = url;
		
	// Get all icons associated with a user
		$icons = db_query("select * from icons where owner = $page_owner");
		if ($page_owner != $_SESSION['userid']) {
			$currenticon = db_query("select icons.filename, users.icon from users left join icons on icons.ident = users.icon where users.ident = $page_owner");
			$currenticon = $currenticon[0]->filename;
		} else {
			$currenticon = $_SESSION['icon'];
		}

              $header = gettext("Site pictures"); // gettext variable
		$body = <<< END
		<h2>
			$header
		</h2>
END;
		
	// If we have some icons, display them; otherwise explain that there isn't anything to edit
		if (sizeof($icons) > 0) {
			
			$desc = gettext("Site pictures are small pictures that act as a representative icon throughout the system."); // gettext variable
                    $body .= <<< END
		<form action="" method="post" />		
			<p>
				$desc
			</p>
END;
			foreach($icons as $icon) {
				list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon->filename);

				$delete = gettext("Delete");
				$name = <<< END
						<label>$delete:
							<input type="checkbox" name="icons_delete[]" value="{$icon->ident}" />
						</label>
END;
				$column1 = <<< END
						<p align="center"><img src="{$url}_icons/data/{$icon->filename}" {$attr} /></p>
END;
				if ($icon->filename == $currenticon) {
					$checked = "checked=\"checked\"";
				} else {
					$checked = "";
				}
				$defaulticon = htmlentities(stripslashes($icon->description));
                           $nameLabel = gettext("Name:");//gettext variable
                           $default = gettext("Default:");//gettext variable
				$column2 = <<< END
						<label>$nameLabel
							<input	type="text" name="description[{$icon->ident}]" 
									value="{$defaulticon}" />
						</label><br />
						<label>$default <input type="radio" name="defaulticon" value="{$icon->ident}" {$checked} /></label>
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
			$noDefault = gettext("No default:");
                     $column1 = <<< END
						<label>$noDefault
						<input type="radio" name="defaulticon" value="-1" {$checked} /></label>
END;
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'column1' => $column1
						)
						);
			$save = gettext("Save"); // gettext variable
                     $body .= <<< END
				<p align="center">
					<input type="hidden" name="action" value="icons:edit" />
					<input type="submit" value=$save />		
				</p>
			</form>
END;
			
		} else {
       
       $noneLoaded = gettext("You don't have any site pictures loaded yet."); // gettext variable
	$body .= <<< END
		<p>
			$noneLoaded
		</p>
END;

		}

		$run_result .= $body;
?>