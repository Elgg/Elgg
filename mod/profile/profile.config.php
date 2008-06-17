<?php

        global $data;
        $run_result = '';
        // Initial profile data

        /* Profile info WAS of the format:
    
        $data['profile:details'][] = array(
                                                Description,
                                                Short / unique internal name,
                                                Type of field,
                                                User instructions for entering data,
                                                Optional: type of user that can see this field
                                                    (e.g. "person", "community" etc)
                                            )
        e.g.
        $data['profile:details'][] = array(__gettext("Interests"),"interests","keywords",__gettext("Separated with commas."));
        
        It is NOW of the format:
        
        $obj = new stdClass;
	$obj->name              // Description
	$obj->internal_name     // Short / unique internal name
	$obj->field_type        // Type of field
	$obj->description       // User instructions for entering data
	$obj->user_type         // Type of user that can see this field
	$obj->required          // True/false: whether field is required
	$obj->category          // Category field sits in
	$obj->invisible         // If true, won't display except on edit screen
	$obj->registration      // Will appear on user registration
	$obj->col1              // If true, will appear on the profile, first column
	                        // otherwise, only on the extended profile
	$obj->default_access    // if $CFG->default_access = 'PUBLIC', set this
	                        // to 'LOGGED_IN' or 'PRIVATE' to force a field to default to
				// 'LOGGED_IN'. This is to avoid users making things
				// public by mistake.
        $data['profile:details'][] = $obj;

        Additions to this data structure will input/output a corresponding FOAF field
        
        $data['foaf:profile'][] = array(
                                            Short / unique internal name,
                                            Corresponding FOAF schema field
                                            "collated" or "individual" -     whether multiple data elements (eg interests)
                                                                            should be in separate tags ("individual") or 
                                                                            in the same tag separated by commas
                                                                            (collated = default)
                                            "resource" or "enclosed" -         whether the data is an rdf:resource="" attribute
                                                                            or enclosed within the tag
                                                                            (resource = default)
                                        )
        e.g.
        $data['foaf:profile'][] = array("interests","foaf:interest");
        
        Also present is $data['vcard:profile:adr'][] for VCard ADR elements within the FOAF file
        e.g.
        $data['vcard:profile:adr'][] = array("streetaddress","vCard:Street","collated");
        */

        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Profile photo"),
                                                                    "internal_name" => "profilephoto",
                                                                    "field_type" => "profile_photo",
                                                                    "description" => __gettext("Photo to display at the top of your profile."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "col1" => true,
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Who am I?"),
                                                                    "internal_name" => "biography",
                                                                    "field_type" => "longtext",
                                                                    "description" => __gettext("A short introduction for you."),
                                                                    "user_type" => "person",
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
								    "col2" => true,
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Introduction"),
                                                                    "internal_name" => "biography",
                                                                    "field_type" => "longtext",
                                                                    "description" => __gettext("A short introduction to this community."),
                                                                    "user_type" => "community",
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
								    "col2" => true,
                                                                    ));

        $data['foaf:profile'][] = array("biography","bio:olb","collated","enclosed");

        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Brief description"),
                                                                    "internal_name" => "minibio",
                                                                    "field_type" => "text",
                                                                    "description" => __gettext("For use in your sidebar profile."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "col1" => true,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));

        // $data['profile:details'][] = array(__gettext("Postal address"),"postaladdress","mediumtext");
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Street address"),
                                                                    "internal_name" => "streetaddress",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Location"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
								    "default_access" => 'PRIVATE',
                                                                    ));
        $data['vcard:profile:adr'][] = array("streetaddress","vCard:Street","collated","enclosed");

        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Town"),
                                                                    "internal_name" => "town",
                                                                    "field_type" => "keywords",
                                                                    "description" => "",
                                                                    "category" => __gettext("Location"),
                                                                    "col1" => true,
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['vcard:profile:adr'][] = array("town","vCard:Locality","collated","enclosed");

        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("State / Region"),
                                                                    "internal_name" => "state",
                                                                    "field_type" => "keywords",
                                                                    "description" => "",
                                                                    "category" => __gettext("Location"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['vcard:profile:adr'][] = array("state","vCard:Region","collated","enclosed");

        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Postal code"),
                                                                    "internal_name" => "postcode",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Location"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['vcard:profile:adr'][] = array("postcode","vCard:Pcode","collated","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Country"),
                                                                    "internal_name" => "country",
                                                                    "field_type" => "keywords",
                                                                    "description" => "",
                                                                    "category" => __gettext("Location"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['vcard:profile:adr'][] = array("country","vCard:Country","collated","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Email address"),
                                                                    "internal_name" => "emailaddress",
                                                                    "field_type" => "email",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Work telephone"),
                                                                    "internal_name" => "workphone",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("workphone","foaf:phone","individual","resource");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Home telephone"),
                                                                    "internal_name" => "homephone",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
								    "default_access" => 'PRIVATE',
                                                                    ));
        $data['foaf:profile'][] = array("homephone","foaf:phone","individual","resource");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Mobile telephone"),
                                                                    "internal_name" => "mobphone",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("mobphone","foaf:phone","individual","resource");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Official website address"),
                                                                    "internal_name" => "workweb",
                                                                    "field_type" => "web",
                                                                    "description" => __gettext("The URL to your official website, if you have one."),
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "col2" => true,
                                                                    "user_type" => "",
								    "default_access" => 'PRIVATE',
                                                                    ));
        $data['foaf:profile'][] = array("workweb","foaf:workplaceHomepage","individual","resource");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Personal website address"),
                                                                    "internal_name" => "personalweb",
                                                                    "field_type" => "web",
                                                                    "description" => __gettext("The URL to your personal website, if you have one."),
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "col2" => true,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("personalweb","foaf:homepage","individual","resource");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("ICQ number"),
                                                                    "internal_name" => "icq",
                                                                    "field_type" => "icq",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("icq","foaf:icqChatID","individual","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("MSN chat"),
                                                                    "internal_name" => "msn",
                                                                    "field_type" => "msn",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("msn","foaf:msnChatID","individual","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("AIM screenname"),
                                                                    "internal_name" => "aim",
                                                                    "field_type" => "aim",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("aim","foaf:aimChatID","individual","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Skype username"),
                                                                    "internal_name" => "skype",
                                                                    "field_type" => "skype",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Jabber username"),
                                                                    "internal_name" => "jabber",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Contact"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("jabber","foaf:jabberChatID","individual","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Interests"),
                                                                    "internal_name" => "interests",
                                                                    "field_type" => "keywords",
                                                                    "description" => __gettext("Separated with commas."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "col2" => true,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("interests","foaf:interest","individual","resource");
        // $data['foaf:profile'][] = array("interests","bio:keywords","collated","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Likes"),
                                                                    "internal_name" => "likes",
                                                                    "field_type" => "keywords",
                                                                    "description" => __gettext("Separated with commas."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Dislikes"),
                                                                    "internal_name" => "dislikes",
                                                                    "field_type" => "keywords",
                                                                    "description" => __gettext("Separated with commas."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Occupation"),
                                                                    "internal_name" => "occupation",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Employment"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Industry"),
                                                                    "internal_name" => "industry",
                                                                    "field_type" => "keywords",
                                                                    "description" => "",
                                                                    "category" => __gettext("Employment"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Company / Institution"),
                                                                    "internal_name" => "organisation",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Employment"),
                                                                    "col2" => true,
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['foaf:profile'][] = array("organisation","foaf:organization","collated","enclosed");
        
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Job Title"),
                                                                    "internal_name" => "jobtitle",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Employment"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Job Description"),
                                                                    "internal_name" => "jobdescription",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Employment"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("I would like to ..."),
                                                                    "internal_name" => "goals",
                                                                    "field_type" => "keywords",
                                                                    "description" => __gettext("Your goals, separated with commas."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Career Goals"),
                                                                    "internal_name" => "careergoals",
                                                                    "field_type" => "longtext",
                                                                    "description" => __gettext("Freeform: let colleagues and potential employers know what you'd like to get out of your career."),
                                                                    "category" => __gettext("Employment"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Level of Education"),
                                                                    "internal_name" => "educationlevel",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Education"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("High School"),
                                                                    "internal_name" => "highschool",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Education"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("University / College"),
                                                                    "internal_name" => "university",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Education"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Degree"),
                                                                    "internal_name" => "universitydegree",
                                                                    "field_type" => "text",
                                                                    "description" => "",
                                                                    "category" => __gettext("Education"),
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));
        $data['profile:details'][] = (object)(array(
                                                                    "name" => __gettext("Main Skills"),
                                                                    "internal_name" => "skills",
                                                                    "field_type" => "keywords",
                                                                    "description" => __gettext("Separated with commas."),
                                                                    "category" => __gettext("Basic details"),
                                                                    "col2" => true,
                                                                    "invisible" => false,
                                                                    "required" => false,
                                                                    "user_type" => "",
                                                                    ));

?>