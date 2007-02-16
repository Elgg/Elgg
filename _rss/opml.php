<?php

	require_once(dirname(dirname(__FILE__))."/includes.php");
	
	$username = trim(optional_param('profile_name',''));
	$user_id = user_info_username("ident", $username);
	
	$date = date("r");
	$output = '';
	
	if ($user_id) {
	
		$owner = $CFG->wwwroot . $username . '/';
		$niceusername = run("profile:display:name", $user_id);
		
		$descr = sprintf(__gettext("Feed subscriptions for %s"), $niceusername);
		
		$output .= <<< END
<opml version="1.1">
	<head>
		<title>$descr</title>
		<dateCreated>$date</dateCreated>
		<ownerName>$owner</ownerName>
	</head>
	<body>
	
END;

		if ($subscriptions_var = newsclient_get_subscriptions_user($user_id, true)) {
			foreach ($subscriptions_var as $afeed) {
				//var_dump($afeed);
				$output .= '<outline type="rss" title="' . htmlspecialchars($afeed->name) . '" htmlUrl="' . htmlspecialchars($afeed->siteurl) . '" xmlUrl="' . htmlspecialchars($afeed->url) . '" text="' . htmlspecialchars($afeed->name) . '" />' . "\n";
			}
		} else {
			$output .= '<outline text="' . __gettext("No subscriptions found") . '" />' . "\n";
		}
	
		$output .= <<< END
	
	</body>
</opml>
END;
	
	} else {
	
		$descr = sprintf(__gettext("Username not found: %s"), $username);
		
		$output .= <<< END
<?xml version="1.0" encoding="UTF-8"?>
<opml version="1.1">
	<head>
		<title>$descr</title>
		<dateCreated>$date</dateCreated>
	</head>
</opml>
END;
		
	}
	
//header("Content-Type: text/plain");
header("Content-Type: text/xml");

//echo 'yo ' .$profile_id;
//echo nl2br(htmlspecialchars($output));
echo $output;



?>