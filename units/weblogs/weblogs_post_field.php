<?php

	// Allow users to post to a weblog if they have permission
	
		if (run("permissions:check", "weblog")) {
/*			
			$clickToAdd = gettext("Click here to post to this weblog."); // gettext variable
			$title = <<< END
				<script language="javascript" type="text/javascript">
					function show(whichLayer) {
							if (document.getElementById)
							{
								// this is the way the standards work
								var style2 = document.getElementById(whichLayer).style;
								style2.display = style2.display? "":"block";
							}
							else if (document.all)
							{
								// this is the way old msie versions work
								var style2 = document.all[whichLayer].style;
								style2.display = style2.display? "":"block";
							}
							else if (document.layers)
							{
								// this is the way nn4 works
								var style2 = document.layers[whichLayer].style;
								style2.display = style2.display? "":"block";
							}
					}
					function hide(whichLayer) {
							if (document.getElementById)
							{
								// this is the way the standards work
								var style2 = document.getElementById(whichLayer).style;
								style2.display = style2.display? "":"none";
							}
							else if (document.all)
							{
								// this is the way old msie versions work
								var style2 = document.all[whichLayer].style;
								style2.display = style2.display? "":"none";
							}
							else if (document.layers)
							{
								// this is the way nn4 works
								var style2 = document.layers[whichLayer].style;
								style2.display = style2.display? "":"none";
							}
					}
				</script> 
				<p>
					<a href="javascript:show('add_weblog_post');">$clickToAdd</a>
				</p>
			
END;
			
			$body = <<< END
			<div id="add_weblog_post" style="display:none; width: 90%">
			
END;
			$body .= run("weblogs:posts:add");
			$hide = gettext("Click here to hide this form."); // gettext variable
			$body .= <<< END
			
				<p>
					<a href="javascript:hide('add_weblog_post');">$hide</a>
				</p>
			
END;

			$body .= <<< END
			
			</div>
			
END;
			*/
			
			$body = <<< END
			
END;

			global $page_owner;
			$clickToAdd = gettext("Click here to post to this weblog.");
			$postUrl = url . "_weblog/edit.php?owner=" . $page_owner;
			$body .= <<< END
			
				<p>
					<a href="$postUrl">$clickToAdd</a>
				</p>
			
END;

			$body .= <<< END
			
			
END;
			
			$run_result .= $body;


		}

?>