<?php

	// Initial profile data

	/* Profile info is of the format:
	
		$data['profile:details'][] = array(
												Description,
												Short / unique internal name,
												Type of field,
												User instructions for entering data
											)
		e.g.
		$data['profile:details'][] = array(gettext("Interests"),"interests","keywords",gettext("Separated with commas."));

		Additions to this data structure will input/output a corresponding FOAF field
		
		$data['foaf:profile'][] = array(
											Short / unique internal name,
											Corresponding FOAF schema field
											"collated" or "individual" - 	whether multiple data elements (eg interests)
																			should be in separate tags ("individual") or 
																			in the same tag separated by commas
																			(collated = default)
											"resource" or "enclosed" - 		whether the data is an rdf:resource="" attribute
																			or enclosed within the tag
																			(resource = default)
										)
		e.g.
		$data['foaf:profile'][] = array("interests","foaf:interest");
		
		Also present is $data['vcard:profile:adr'][] for VCard ADR elements within the FOAF file
		e.g.
		$data['vcard:profile:adr'][] = array("streetaddress","vCard:Street","collated");
	*/
	
		$data['profile:details'][] = array(gettext("Who am I?"),"biography","longtext",gettext("A short introduction for you."));
		$data['foaf:profile'][] = array("biography","bio:olb","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Brief description"),"minibio","text",gettext("For use in your sidebar profile."));
		
		// $data['profile:details'][] = array(gettext("Postal address"),"postaladdress","mediumtext");
		$data['profile:details'][] = array(gettext("Street address"),"streetaddress","text");
		$data['vcard:profile:adr'][] = array("streetaddress","vCard:Street","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Town"),"town","keywords");
		$data['vcard:profile:adr'][] = array("town","vCard:Locality","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("State / Region"),"state","keywords");
		$data['vcard:profile:adr'][] = array("state","vCard:Region","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Postal code"),"postcode","text");
		$data['vcard:profile:adr'][] = array("postcode","vCard:Pcode","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Country"),"country","keywords");
		$data['vcard:profile:adr'][] = array("country","vCard:Country","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Email address"),"emailaddress","email");
		
		$data['profile:details'][] = array(gettext("Work telephone"),"workphone","text");
		$data['foaf:profile'][] = array("workphone","foaf:phone","individual","resource");
		
		$data['profile:details'][] = array(gettext("Home telephone"),"homephone","text");
		$data['foaf:profile'][] = array("homephone","foaf:phone","individual","resource");
		
		$data['profile:details'][] = array(gettext("Mobile telephone"),"mobphone","text");
		$data['foaf:profile'][] = array("mobphone","foaf:phone","individual","resource");
		
		$data['profile:details'][] = array(gettext("Official website address"),"workweb","web",gettext("The URL to your official website, if you have one."));
		$data['foaf:profile'][] = array("workweb","foaf:workplaceHomepage","individual","resource");
		
		$data['profile:details'][] = array(gettext("Personal website address"),"personalweb","web",gettext("The URL to your personal website, if you have one."));
		$data['foaf:profile'][] = array("personalweb","foaf:homepage","individual","resource");
		
		$data['profile:details'][] = array(gettext("ICQ number"),"icq","icq");
		$data['foaf:profile'][] = array("icq","foaf:icqChatID","individual","enclosed");
		
		$data['profile:details'][] = array(gettext("MSN chat"),"msn","msn");
		$data['foaf:profile'][] = array("msn","foaf:msnChatID","individual","enclosed");
		
		$data['profile:details'][] = array(gettext("AIM screenname"),"aim","aim");
		$data['foaf:profile'][] = array("aim","foaf:aimChatID","individual","enclosed");
		
		$data['profile:details'][] = array(gettext("Skype username"),"skype","skype");
		
		$data['profile:details'][] = array(gettext("Jabber username"),"jabber","text");
		$data['foaf:profile'][] = array("jabber","foaf:jabberChatID","individual","enclosed");
		
		$data['profile:details'][] = array(gettext("Interests"),"interests","keywords",gettext("Separated with commas."));
		$data['foaf:profile'][] = array("interests","foaf:interest","individual","resource");
		// $data['foaf:profile'][] = array("interests","bio:keywords","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Likes"),"likes","keywords",gettext("Separated with commas."));
		$data['profile:details'][] = array(gettext("Dislikes"),"dislikes","keywords",gettext("Separated with commas."));
		$data['profile:details'][] = array(gettext("Occupation"),"occupation","text");
		$data['profile:details'][] = array(gettext("Industry"),"industry","keywords");
		
		$data['profile:details'][] = array(gettext("Company / Institution"),"organisation","text");
		$data['foaf:profile'][] = array("organisation","foaf:organization","collated","enclosed");
		
		$data['profile:details'][] = array(gettext("Job Title"),"jobtitle","text");
		$data['profile:details'][] = array(gettext("Job Description"),"jobdescription","text");
		$data['profile:details'][] = array(gettext("I would like to ..."),"goals","keywords",gettext("Separated with commas."));
		$data['profile:details'][] = array(gettext("Career Goals"),"careergoals","longtext",gettext("Freeform: let colleagues and potential employers know what you'd like to get out of your career."));
		$data['profile:details'][] = array(gettext("Level of Education"),"educationlevel","text");
		$data['profile:details'][] = array(gettext("High School"),"highschool","text");
		$data['profile:details'][] = array(gettext("University / College"),"university","text");
		$data['profile:details'][] = array(gettext("Degree"),"universitydegree","text");
		$data['profile:details'][] = array(gettext("Main Skills"),"skills","keywords",gettext("Separated with commas."));
		
?>