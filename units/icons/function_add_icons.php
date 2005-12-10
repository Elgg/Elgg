<?php

	global $page_owner;

	// Allow the user to add more icons
		$numicons = db_query("select count(ident) as iconnum from icons where owner = " . $page_owner);
		$numicons = $numicons[0]->iconnum;
		if ($page_owner != $_SESSION['userid']) {
			$iconquota = db_query("select icon_quota from users where ident = " . $page_owner);
			$iconquota = $iconquota[0]->icon_quota;
		} else {
			$iconquota = $_SESSION['icon_quota'];
		}
		
		if ($numicons < $iconquota) {
                    
                    $header = gettext("Upload a new picture"); // gettext variable
                    $desc = gettext("Upload a picture for this profile below. Pictures need to be 100x100 pixels or smaller, but don't worry - if you've selected a larger picture, we'll shrink it down for you. You may upload up to"); // gettext variable
                    $desc_two = gettext("pictures in total."); // gettext variable
			$body = <<< END
			<p>
				<h2>$header</h2>
			</p>
			<p>
				$desc
				{$iconquota} $desc_two
			</p>
			<form action="" method="post" enctype="multipart/form-data">
END;
			$name = "<label for=\"iconfile\">" . gettext("Picture to upload:") . "</label>";
			$column1 = "
						<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2000000\" />
						<input name=\"iconfile\" id=\"iconfile\" type=\"file\" />
						";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$name = "<label for=\"icondescription\">". gettext("Icon description:") ."</label>";
			$column1 = "<input type=\"text\" name=\"icondescription\" id=\"icondescription\" value=\"\" />";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$name = "<label for=\"icondefault\">" . gettext("Make this the default icon:") . "</label>";
			$column1 = "
							<select name=\"icondefault\" id=\"icondefault\">
								<option value=\"yes\">".gettext("Yes")."</option>
								<option value=\"no\">".gettext("No")."</option>
							</select>
						";
			$body .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1
						)
						);
			$upload = gettext("Upload new icon"); // gettext variable
                    $body .= <<< END
						<p align="center"><input type="hidden" name="action" value="icons:add" />
							<input type="submit" value=$upload /></p>
			</form>

END;
		} else {
			$iconQuota = sprintf(gettext("The icon quota is 1st: $s and you have 2nd: $s icons uploaded. You may not upload any more icons until you've deleted some."),$iconquota,$numicons); // gettext variable NOT SURE!!!
                     $body = <<< END
			<p>
				$iconQuota
			</p>
END;
		}

	$run_result .= $body;
		
?>
