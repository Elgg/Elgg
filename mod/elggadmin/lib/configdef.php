<?php

            $DEFCFG->config['sitename']->name = __gettext("Site name");
            $DEFCFG->config['sitename']->description = __gettext("Enter the name of your site here (eg Elgg, Apcala, University of Bogton's Social Network, etc)");
            $DEFCFG->config['sitename']->important = true;

            $DEFCFG->config['tagline']->name = __gettext("Tagline");
            $DEFCFG->config['tagline']->description = __gettext("A tagline for your site (eg 'Social network for Bogton')");

            $DEFCFG->config['wwwroot']->name = __gettext("Web root");
            $DEFCFG->config['wwwroot']->description = __gettext("External URL to the site (eg http://elgg.bogton.edu/). NB: **MUST** have a final slash at the end");
            $DEFCFG->config['wwwroot']->hidden = true;

            $DEFCFG->config['dirroot']->name = __gettext("Elgg install root");
            $DEFCFG->config['dirroot']->description = __gettext("Physical path to the files (eg /home/elggserver/httpdocs/). NB: **MUST** have a final slash at the end");
            $DEFCFG->config['dirroot']->noteditable = true;

            $DEFCFG->config['dataroot']->name = __gettext("Elgg data root");
            $DEFCFG->config['dataroot']->description = __gettext("This is a special directory where uploaded files will go. If possible, this should live outside your main Elgg install (you'll need to create it by hand). It must have world writable permissions set, and have a final slash at the end.");
            $DEFCFG->config['dataroot']->important = true;
            $DEFCFG->config['dataroot']->hidden = true;

            $DEFCFG->config['dbtype']->name = __gettext("Database type");
            $DEFCFG->config['dbtype']->description = __gettext("Acceptable values are 'mysql' and 'postgres' - MySQL is highly recommended");
            $DEFCFG->config['dbtype']->important = true;
            $DEFCFG->config['dbtype']->hidden = true;

            $DEFCFG->config['dbname']->name = __gettext("Database name");
            $DEFCFG->config['dbname']->important = true;

            $DEFCFG->config['dbhost']->name = __gettext("Database host");
            $DEFCFG->config['dbhost']->important = true;
            $DEFCFG->config['dbhost']->hidden = true;

            $DEFCFG->config['dbuser']->name = __gettext("Database user");
            $DEFCFG->config['dbuser']->important = true;
            $DEFCFG->config['dbuser']->hidden = true;

            $DEFCFG->config['dbpass']->name = __gettext("Database password");
            $DEFCFG->config['dbpass']->important = true;

            $DEFCFG->config['prefix']->name = __gettext("Database table prefix");
            $DEFCFG->config['prefix']->description = __gettext("All database tables will start with this; we recommend 'elgg_'");
            $DEFCFG->config['prefix']->important = true;

            $DEFCFG->config['sysadminemail']->name = __gettext("System administrator email");
            $DEFCFG->config['sysadminemail']->description = __gettext("The email address your site will send email from (eg elgg-admin@bogton.edu)");
            $DEFCFG->config['sysadminemail']->important = true;

            $DEFCFG->config['defaultlocale']->name = __gettext("Default locale");
            $DEFCFG->config['defaultlocale']->description = __gettext("Country code to set language to if you have gettext installed");
            $DEFCFG->config['defaultlocale']->type = 'language';

            $DEFCFG->config['default_template']->name = __gettext('Default Template');
            $DEFCFG->config['default_template']->description = __gettext('The default template of the site');
            $DEFCFG->config['default_template']->type = 'template';

            $DEFCFG->config['publicreg']->name = __gettext("Public registration");
            $DEFCFG->config['publicreg']->description = __gettext("Can general members of the public register for this system?");
            $DEFCFG->config['publicreg']->type = "boolean";

            $DEFCFG->config['publicinvite']->name = __gettext("Public invitations");
            $DEFCFG->config['publicinvite']->description = __gettext("Can users of this system invite other users in?");
            $DEFCFG->config['publicinvite']->type = "boolean";

            $DEFCFG->config['maxusers']->name = __gettext("Maximum users");
            $DEFCFG->config['maxusers']->description = __gettext("The maximum number of users in your system. If you set this to 0, you will have an unlimited number of users");
            $DEFCFG->config['maxusers']->type = "integer";

            $DEFCFG->config['maxspace']->name = __gettext("Maximum disk space");
            $DEFCFG->config['maxspace']->description = __gettext("The maximum disk space taken up by all uploaded files");
            $DEFCFG->config['maxspace']->type = "integer";
            $DEFCFG->config['maxspace']->hidden = true;

            $DEFCFG->config['walledgarden']->name = __gettext("Walled garden");
            $DEFCFG->config['walledgarden']->description = __gettext("If your site is a walled garden, nobody can see anything from the outside. This will also mean that RSS feeds won't work");
            $DEFCFG->config['walledgarden']->type = "boolean";

            $DEFCFG->config['disable_publiccomments']->name = __gettext("Disable public comments");
            $DEFCFG->config['disable_publiccomments']->description = __gettext("Set the following to true to force users to log in before they can post comments, overriding the per-user option. This is a handy sledgehammer-to-crack-a-nut tactic to protect against comment spam (although an Akismet plugin is available from elgg.org).");
            $DEFCFG->config['disable_publiccomments']->type = "boolean";

            $DEFCFG->config['emailfilter']->name = __gettext("Email filter");
            $DEFCFG->config['emailfilter']->description = __gettext("Anything you enter here must be present in the email address of anyone who registers; e.g. @mycompany.com will only allow email address from mycompany.com to register");
            $DEFCFG->config['emailfilter']->hidden = true;

            $DEFCFG->config['default_access']->name = __gettext("Default access");
            $DEFCFG->config['default_access']->description = __gettext("The default access level for all new items in the system");
            $DEFCFG->config['default_access']->type = 'access';

            $DEFCFG->config['disable_usertemplates']->name = __gettext("Disable user templates");
            $DEFCFG->config['disable_usertemplates']->description = __gettext("If this is set, users can only choose from available templates rather than defining their own");
            $DEFCFG->config['disable_usertemplates']->type = "boolean";
            $DEFCFG->config['disable_usertemplates']->hidden = true;

            $DEFCFG->config['disable_templatechanging']->name = __gettext("Disable template changing");
            $DEFCFG->config['disable_templatechanging']->description = __gettext("Users cannot change their template at all");
            $DEFCFG->config['disable_templatechanging']->type = "boolean";
            $DEFCFG->config['disable_templatechanging']->hidden = true;

            $DEFCFG->config['dbpersist']->name = __gettext("Persistent connections");
            $DEFCFG->config['dbpersist']->description = __gettext("Should Elgg use persistent database connections?");
            $DEFCFG->config['dbpersist']->type = "boolean";
            $DEFCFG->config['dbpersist']->noteditable = true;

            $DEFCFG->config['debug']->name = __gettext("Debug");
            $DEFCFG->config['debug']->description = __gettext("Set this to <em>Show all errors</em> to get adodb error handling");
            $DEFCFG->config['debug']->type = 'debug';

            $DEFCFG->config['rsspostsmaxage']->name = __gettext("RSS posts maximum age");
            $DEFCFG->config['rsspostsmaxage']->description = __gettext("Number of days to keep incoming RSS feed entries for before deleting them. Set this to 0 if you don't want RSS posts to be removed.");
            $DEFCFG->config['rsspostsmaxage']->type = "integer";
            $DEFCFG->config['rsspostsmaxage']->hidden = true;

            $DEFCFG->config['community_create_flag']->name = __gettext("Community create flag");
            $DEFCFG->config['community_create_flag']->description = __gettext("Set this to 'admin' if you would like to restrict the ability to create communities to admin users.");
            $DEFCFG->config['community_create_flag']->hidden = true;

            $DEFCFG->config['curlpath']->name = __gettext("CURL path");
            $DEFCFG->config['curlpath']->description = __gettext("Set this to the CURL executable if CURL is installed; otherwise leave blank.");
            $DEFCFG->config['curlpath']->hidden = true;

            $DEFCFG->config['templatesroot']->name = __gettext("Templates location");
            $DEFCFG->config['templatesroot']->description = __gettext("The full path of your Default_Template directory");
            $DEFCFG->config['templatesroot']->hidden = true;

            $DEFCFG->config['profilelocation']->name = __gettext("Profile location");
            $DEFCFG->config['profilelocation']->description = __gettext("The full path to your profile configuration file (usually it's best to leave this in mod/profile/)");
            $DEFCFG->config['profilelocation']->hidden = true;
            
?>