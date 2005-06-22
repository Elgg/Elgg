<?php

	global $page_owner;
	
	$redirect = url . run("users:id_to_name", $page_owner) . "/weblog/";

	$username = $_SESSION['username'];
	$body = <<< END

<form method="post" name="elggform" action="$redirect" onsubmit="return submitForm();">

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
		Keywords (<b>separate with commas</b>):<br />
              Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting.
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