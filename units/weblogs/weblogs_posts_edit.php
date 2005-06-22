<?php

	// $username = $_SESSION['username'];
	
	$post = db_query("select * from weblog_posts where ident = " . $parameter);
	$post = $post[0];
	
	$url = url;
	
	global $page_owner;
	$page_owner = $post->weblog;
	
	$username = run("users:id_to_name", $post->weblog);
	
	if ($post->owner != $_SESSION['userid']) {
		exit();
	}
	
	$body = <<< END

<form method="post" name="elggform" action="{$url}{$username}/weblog/{$post->ident}.html" onsubmit="return submitForm();">

	<h2>Edit a post</h2>
	<p>
		Post title:<br />
END;
	
	$body .= run("display:input_field",array("edit_weblog_title",stripslashes($post->title),"text"));
	$body .= <<< END

	</p>
	
	<p>Post body:<br />
END;

	$body .= run("display:input_field",array("new_weblog_post",stripslashes($post->body),"longtext"));

	$body .= <<< END
	</p>
	<p>
		Keywords (<b>Separated by commas</b>):<br />
              Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting.
END;
	$body .= run("display:input_field",array("edit_weblog_keywords","","keywords","weblog",$post->ident));
	$body .= <<< END
	</p>
	<p>
		Access restrictions:<br />

END;

	$body .= run("display:access_level_select",array("edit_weblog_access",$post->access));
	$body .= <<< END
	</p>
END;
	$body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
	$body .= <<< END
	<p>
		<input type="hidden" name="action" value="weblogs:post:edit" />
		<input type="hidden" name="edit_weblog_post_id" value="{$post->ident}" />
		<input type="submit" value="Save Post" />
	</p>

</form>
END;

	$run_result .= $body;

?>