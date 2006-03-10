<?php
	$selected_month = (int) $parameter[0];
	$selected_year = (int) $parameter[1];
	$context = $parameter[2];
		
	global $page_owner;
	global $months;
	
	$calendar_username = run("users:id_to_name",$page_owner);
	
	$previous_date = getdate(mktime(0, 0, 0, ($selected_month != 1 ? $selected_month - 1 : 12), 0, ($selected_month != 1 ? $selected_year : $selected_year - 1)));
	$following_date = getdate(mktime(0, 0, 0, ($selected_month != 13 ? $selected_month + 1 : 2), 0, ($selected_month != 13 ? $selected_year : $selected_year + 1)));
	
		
	$previous_date_url = url . "_calendar/index.php?selected_month=" . ($selected_month != 1 ? $selected_month - 1 : 12) . "&amp;selected_year=" . ($selected_month != 1 ? $selected_year : $selected_year - 1) . ($context != "" ? "&amp;context=" . $context : "");
	$following_date_url = url . "_calendar/index.php?selected_month=" . ($selected_month != 13 ? $selected_month + 1 : 2) . "&amp;selected_year=" . ($selected_month != 13 ? $selected_year : $selected_year + 1) . ($context != "" ? "&amp;context=" . $context : "");
	

	$run_result = array("<a href=\"{$previous_date_url}\">&lt;&lt;&nbsp;{$months[$previous_date["month"]]}</a>",
						"<a href=\"{$following_date_url}\">{$months[$following_date["month"]]}&nbsp;&gt;&gt;</a>");

?>
