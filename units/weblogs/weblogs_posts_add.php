<?php

	global $page_owner;

	if (!run("permissions:check", "weblog")) {
		if (logged_on) {
			$page_owner = $_SESSION['userid'];
		} else {
			$page_owner = -1;
		}
	}
	
	$redirect = url . run("users:id_to_name", $page_owner) . "/weblog/";
	
	$username = $_SESSION['username'];
	$addPost = gettext("Add a new post"); // gettext variable
	$postTitle = gettext("Post title:"); // gettext variable
	$postBody = gettext("Post body:"); // gettext variable
	$Keywords = gettext("Keywords (Separated by commas):"); // gettext variable
	$keywordDesc = gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting."); // gettext variable
	$accessRes = gettext("Access restrictions:"); // gettext variable
	$postButton = gettext("Post"); // gettext variable
	
	$body = <<< END

<form method="post" name="elggform" action="$redirect" onsubmit="return submitForm();">

	<h2>$addPost</h2>
	
END;

	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $postTitle,
								'contents' => run("display:input_field",array("new_weblog_title","","text"))
							)
							);
							
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $postBody,
								'contents' => run("display:input_field",array("new_weblog_post",stripslashes($post->body),"weblogtext"))
							)
							);

	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $Keywords . "<br />" . $keywordDesc,
								'contents' => run("display:input_field",array("new_weblog_keywords","","keywords","weblog"))
							)
							);

	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $accessRes,
								'contents' => run("display:access_level_select",array("new_weblog_access","user" . $_SESSION['userid']))
							)
							);

	$body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
	$body .= <<< END
	<p>
		<input type="hidden" name="action" value="weblogs:post:add" />
		<input type="submit" value="$postButton" />
	</p>

</form>
END;

	$run_result .= $body;

?>