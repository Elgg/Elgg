<?php

		global $page_owner;
		$profile_id = $page_owner;
	
		$title = "Search";

		$body = <<< END
		<form style="margin: 0px; padding: 0px" name="searchform" action="/search/all.php">
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
				<a href="/search/tags.php">Random tags</a>
			</p>
		</form>

END;

		$run_result .= "<div class=\"box_search\">";
		$run_result .= run("templates:draw", array(
								'context' => 'infobox',
								'name' => $title,
								'contents' => $body
							)
							);
		$run_result .= "</div>";

?>