<?php

	if (isset($parameter)) {
	
		$post = $parameter;
		
		$run_result .= <<< END
		
	<form action="" method="post">
	
		<h2>Add a comment</h2>
	
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
								'name' => "Your comment text",
								'column1' => $field
		
							)
							);
							
		if (logged_on) {
			$comment_name = $_SESSION['name'];
		} else {
			$comment_name = "Guest";
		}

		$run_result .= run("templates:draw", array(
		
								'context' => 'databox1',
								'name' => 'Your name',
								'column1' => "<input type=\"text\" name=\"postedname\" value=\"".htmlentities($comment_name)."\" />"
		
							)
							);
		
		$run_result .= run("templates:draw", array(
		
								'context' => 'databox1',
								'name' => '&nbsp;',
								'column1' => "<input type=\"submit\" value=\"Add comment\" />"
		
							)
							);
							
		$run_result .= <<< END
	
	</form>
		
END;
		
	}

?>