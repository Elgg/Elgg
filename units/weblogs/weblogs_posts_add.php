<?php

	$username = $_SESSION['username'];
	$body = <<< END

<form method="post" name="elggform" action="/_weblog/action_redirection.php" onsubmit="return submitForm();">

	<h2>Add a new post</h2>
	<p>
		Post title:<br />
END;
	
	$body .= run("display:input_field",array("new_weblog_title","","text"));
	$body .= <<< END

	</p>
	
	<p>Post body:<br />
END;

	$body .= run("display:input_field",array("new_weblog_post","","longtext"));

	$body .= <<< END
	</p>
	<p>
		Keywords:<br />
END;
	$body .= run("display:input_field",array("new_weblog_keywords","","keywords","weblog"));
	$body .= <<< END
	</p>
	<p>
		Access restrictions:<br />

END;

	$body .= run("display:access_level_select",array("new_weblog_access","PUBLIC"));
	$body .= <<< END
	</p>
END;
	$body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
	$body .= <<< END
	<p>
		<input type="hidden" name="action" value="weblogs:post:add" />
		<input type="submit" value="Post" />
	</p>

</form>
END;

	$run_result .= $body;

?>