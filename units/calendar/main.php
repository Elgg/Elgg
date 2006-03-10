<?php
	
		// Menu button
		$function['menu:main'][] = path . "units/calendar/menu_main.php";	
		$function['menu:sub'][] = path . "units/calendar/menu_sub.php";
		
		// Load default template
		$function['init'][] = path . "units/calendar/default_template.php";
		
		$function['calendar:init'][] = path ."units/calendar/calendar_init.php";
		$function['calendar:init'][] = path ."units/calendar/calendar_actions.php";
	
		// Init for search
		$function['search:init'][] = path . "units/calendar/calendar_init.php";
		$function['search:all:tagtypes'][] = path . "units/calendar/function_search_all_tagtypes.php";		
		
		// Function to search through weblog posts
		$function['search:display_results'][] = path . "units/calendar/function_search.php";
		// $function['search:display_results:rss'][] = path . "units/calendar/function_search_rss.php";
		
		$function['calendar:display:monthly'][] = path ."units/calendar/calendar_display_monthly.php";
		$function['calendar:blog:view'][] = path ."units/calendar/calendar_blog_view.php";
		
		// add, edit, import a calendar
		$function['calendar:add_event'][] = path ."units/calendar/calendar_add_event.php";
		$function['calendar:edit_event'] [] = path ."units/calendar/calendar_edit_event.php";
		$function['calendar:import_ical'][] = path . "units/calendar/calendar_import_ical.php";
		$function['calendar:ical_parser'][] = path . "units/calendar/calendar_ical_parser.php";
		
		$function['calendar:get_id_from_owner'][] = path ."units/calendar/function_get_id_from_owner.php";
		$function['calendar:get_monthly_event_listings'][] = path . "units/calendar/function_get_monthly_event_listings.php";
		$function['calendar:get_daily_event_listings'][] = path . "units/calendar/function_get_daily_event_listings.php";
		$function['calendar:get_event'][] = path . "units/calendar/function_get_event.php";
		$function['calendar:get_person_type_friends'][] = path . "units/calendar/function_get_person_type_friends.php";
		$function['calendar:get_friend_calendar_ids'][] = path . "units/calendar/function_get_friend_calendar_ids.php";
		$function['calendar:get_friend_event_listings'][] = path . "units/calendar/function_get_friend_event_listings.php";
		$function['calendar:get_community_event_listings'][] = path . "units/calendar/function_get_community_event_listings.php";
		
		$function['calendar:display:dates'][] = path . "units/calendar/display_dates.php";
		$function['calendar:display:monthly_navigation'][] = path . "units/calendar/display_monthly_navigation.php";
		
		// archives
		$function['calendar:archives:view'][] = path . "units/calendar/archives_view.php";
		$function['calendar:archives:month:view'][] = path . "units/calendar/archives_view_month.php";
		$function['calendar:events:view'][] = path . "units/calendar/calendar_events_view.php";
		
		//iCal export
		$function['calendar:export:event'][] = path . "units/calendar/calendar_export_event.php";
		
		// calendar preview
		$function['templates:preview'][] = path . "units/calendar/templates_preview.php";
		
		$function['display:sidebar'][] = path ."units/calendar/calendar_user_info_menu.php";
?>