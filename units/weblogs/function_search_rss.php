<?php

	global $search_exclusions;

	if (isset($parameter) && $parameter[0] == "weblog" || $parameter[0] == "weblogall") {
		
		$search_exclusions[] = "weblogall";
		$owner = (int) $_REQUEST['owner'];
		$searchline = "tagtype = 'weblog' and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("access", "weblog_posts.access", $searchline);
		$searchline = str_replace("owner", "weblog_posts.weblog", $searchline);
		$refs = db_query("select weblog_posts.owner, weblog_posts.weblog, weblog_posts.ident, weblog_posts.title, users.name, tags.ref from tags join weblog_posts on weblog_posts.ident = ref join users on users.ident = tags.owner where $searchline order by weblog_posts.posted desc limit 50");
		
		if (sizeof($refs) > 0) {
			foreach($refs as $post) {
				$run_result .= "\t<item>\n";
				$run_result .= "\t\t<title><![CDATA[" . gettext("Weblog post") . " :: " . (stripslashes($post->name));
				if ($post->title != "") {
					$run_result .= " :: " . (stripslashes($post->title));
				}
				$weblogusername = run("users:id_to_name",$post->weblog);
				$run_result .= "]]></title>\n";
				$run_result .= "\t\t<link>" . url . (stripslashes($weblogusername)) . "/weblog/" . $post->ident . ".html</link>\n";
				$run_result .= "\t</item>\n";
			}
		}
		
	}

?>