<?php

	if (isset($parameter)) {
	
		$post = $parameter;
		
              $addComment = gettext("Add a comment"); // gettext variable
		$run_result .= <<< END
		
	<form action="" method="post">
	
		<h2>$addComment</h2>
	
END;

		$field = run("display:input_field",array("new_weblog_comment","","longtext"));
		if (logged_on) {
			$userid = $_SESSION['userid'];
		} else {
			$userid = -1;
		}
		$field .= <<< END
		
		<input type="hidden" name="action" value="weblogs:comment:add" />
		<input type="hidden" name="post_id" value="{$post->ident}" />
		<input type="hidden" name="owner" value="{$userid}" />
		
END;

		$run_result .= run("templates:draw", array(
		
								'context' => 'databox1',
								'name' => gettext("Your comment text"),
								'column1' => $field
		
							)
							);
							
		if (logged_on) {
			$comment_name = $_SESSION['name'];
		} else {
			$comment_name = gettext("Guest");
		}

		$run_result .= run("templates:draw", array(
		
								'context' => 'databox1',
								'name' => gettext("Your name"),
								'column1' => "<input type=\"text\" name=\"postedname\" value=\"".htmlentities($comment_name)."\" />"
		
							)
							);
		
		$run_result .= run("templates:draw", array(
		
								'context' => 'databox1',
								'name' => '&nbsp;',
								'column1' => "<input type=\"submit\" value=\"".gettext("Add comment")."\" />"
		
							)
							);
							
		$run_result .= <<< END
	
	</form>
		
END;
		
	}

?>