<?php

	$user_id = (int) $parameter;
	
	$user = db_query("select * from users where ident = $user_id");
	
	if (sizeof($user) > 0) {

		$user = $user[0];
		$url = url;
		$adminmail = email;
		$personalurl = url . $user->username . "/";
		$username = htmlentities(stripslashes($user->username));
		$name = htmlentities(stripslashes($user->name));
		$shamail = sha1("mailto:" . $user->email);
		
		if ($user->icon != -1) {
			$icon = db_query("select * from icons where ident = " . $user->icon);
			$icon = $icon[0];
			$iconstring = "<foaf:depiction rdf:resource=\"". url . "_icons/data/".$icon->filename."\" />";
		} else {
			$iconstring = "";
		}
		
		$run_result .= <<< END
<?xml version='1.0'?>
<rdf:RDF
		xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
		xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:bio="http://purl.org/vocab/bio/0.1/"
		xmlns:vCard="http://www.w3.org/2001/vcard-rdf/3.0#"
		xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
		xmlns:admin="http://webns.net/mvcb/"
		xmlns:rel="http://purl.org/vocab/relationship/"
		xmlns:foaf="http://xmlns.com/foaf/0.1/">
	<foaf:PersonalProfileDocument rdf:about="">
		<foaf:maker rdf:nodeID="elgg{$user->ident}"/>
		<foaf:primaryTopic rdf:nodeID="elgg{$user->ident}"/>
		<admin:generatorAgent rdf:resource="{$url}"/>
		<admin:errorReportsTo rdf:resource="{$adminmail}"/>
	</foaf:PersonalProfileDocument>
	<foaf:Person rdf:nodeID="elgg{$user->ident}">
		<foaf:nick>{$username}</foaf:nick>
		<foaf:name>{$name}</foaf:name>
		<foaf:mbox_sha1sum>{$shamail}</foaf:mbox_sha1sum>
		{$iconstring}
END;
		$run_result .= run("foaf:generate:fields",$parameter);

		$run_result .= "\t\t<vCard:ADR rdf:parseType=\"Resource\">\n";
		$run_result .= run("vcard:generate:fields:adr",$parameter);
		$run_result .= "\t\t</vCard:ADR>\n";
		
		$friends = db_query("select users.* from friends join users on users.ident = friends.friend where friends.owner = " . $user->ident);
		if (sizeof($friends) > 0) {
			foreach($friends as $friend) {
				$name = htmlentities(stripslashes($friend->name));
				$email = htmlentities(sha1("mailto:" . $friend->email));
				$username = htmlentities(stripslashes($friend->username));
				$personalurl = url . $username . "/";
				$foafurl = $personalurl . "foaf/";
				if ($friend->icon != -1) {
					$icon = db_query("select * from icons where ident = " . $friend->icon);
					$icon = $icon[0];
					$iconstring = "<foaf:depiction rdf:resource=\"". url . "_icons/data/".$icon->filename."\" />";
				} else {
					$iconstring = "";
				}
				$extras = run("foaf:knows:elements", $friend->ident);
				$run_result .= <<< END

		<foaf:knows>
			<foaf:Person>
				<foaf:nick>{$username}</foaf:nick>
				<foaf:name>{$name}</foaf:name>
				<foaf:mbox_sha1sum>{$email}</foaf:mbox_sha1sum>
				<foaf:homepage rdf:resource="{$personalurl}"/>
				<rdfs:seeAlso rdf:resource="{$foafurl}"/>
				{$iconstring}
				{$extras}
			</foaf:Person>
		</foaf:knows>
END;
			}
		}

		$run_result .= run("foaf:elements",$user_id);
		$run_result .= <<< END
	
	</foaf:Person>
</rdf:RDF>

END;

	}

?>