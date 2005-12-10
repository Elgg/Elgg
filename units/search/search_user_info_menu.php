<?php

		global $page_owner;
		$profile_id = $page_owner;
	
		$title = gettext("Search");

		$url=url;
              $randomTags = gettext("Random tags"); // gettext variable
		
		$body = <<< END
		<form style="margin: 0px; padding: 0px" name="searchform" action="{$url}search/all.php">
		 	<script language="JavaScript" type="text/javascript">
				<!--
				function submitthis()
				{
				  document.searchform.submit() ;
				}
				-->
			</script>
			<p align="center">
				<input name="tag" type="text" value="" style="width: 110px">&nbsp;<a href="javascript:submitthis()" style="text-decoration: none">&gt;&gt;</a><br />
				<a href="{$url}search/tags.php">$randomTags</a>
			</p>
		</form>

END;

		$run_result .= run("templates:draw", array(
								'context' => 'contentholder',
								'title' => $title,
								'body' => $body,
								'submenu' => ''
							)
							);

?>