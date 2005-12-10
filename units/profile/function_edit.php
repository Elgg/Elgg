<?php

	global $page_owner;

	if (run("permissions:check", "profile")) {
	
		$profile_username = run("users:id_to_name",$page_owner);
		
		$body = "<form action=\"".url . "profile/edit.php?profile_id=".$page_owner."\" method=\"post\" enctype=\"multipart/form-data\">";
		$body .= "<p>" . gettext("You can import some profile data by uploading a FOAF file here:") . "</p>";
		$body .= run("templates:draw", array(
					'context' => 'databox',
					'name' => gettext("Upload a FOAF file:"),
					'column1' => "<input name=\"foaf_file\" id=\"foaf_file\" type=\"file\" />",
					'column2' => "<input type=\"submit\" value=\"".gettext("Upload") . "\" />"
				)
				);
		$body .= <<< END
		
		<input type="hidden" name="action" value="profile:foaf:upload" />
		<input type="hidden" name="profile_id" value="$page_owner" />
	</form>
		
END;
		$body .= "<p>" .gettext("Or you can fill in your profile directly below:") . "</p>";
		$body .= "<form action=\"".url . $profile_username ."/\" method=\"post\">";
	
		// Cycle through all defined profile detail fields and display them
	
		if (isset($data['profile:details']) && sizeof($data['profile:details']) > 0) {
		
			foreach($data['profile:details'] as $field) {
				$body .= run("profile:editfield:display",$field);
			}
	
		}
	
		$submitMsg = gettext("Submit details:");
              $saveProfile = gettext("Save your profile");
		$body .= <<< END
      
	<p align="center">
		<label>
			$submitMsg
			<input type="submit" name="submit" value="$saveProfile" />
		</label>
		<input type="hidden" name="action" value="profile:edit" />
		<input type="hidden" name="profile_id" value="$page_owner" />
	</p>

</form>
END;

		$run_result .= $body;
	
	}
	
?>