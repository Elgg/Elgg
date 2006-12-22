MODULES
-------

These are main modules in Elgg, allowing various activities.

Each of these modules contains a number of expected components:

  version.php: defines some meta-info and provides upgrading code

  db/mysql.sql     SQL dumps of all the required db tables and data
  db/postgres7.sql if you only have access to one of them, feel free to
                   ask for help on development@elgg.org

  index.php: a page to list all instances in a course

  view.php: a page to view a particular instance

  lib.php: functions defined by the module should be in here.
         constants should be defined using MODULENAME_xxxxxx
         functions should be defined using modulename_xxxxxx

         There are a number of standard functions:

	 modulename_pagesetup() called on every page just before
                    templates are parsed. Use to populate $PAGE.

         modulename_cron() -- gets called every 5'
           Also, optional 
             modulename_cron_maint() -- gets called ~ every hour
             modulename_cron_daily() -- gets called once a day

        Note: we strive to keep lib.php lightweight and without 
              side-effects, as it is pulled in by every request. 
              Large functions needed only from some pages are
              are better defined in an extraslib.php or
              <edit>lib.php.

If you are a developer and interested in developing new Modules see:
  
   Elgg Developer Wiki:         http://elgg.org/info/
   Elgg Developer Mailinglist:  http://lists.elgg.org/mailman/listinfo/development
   Elgg Community               http://elgg.net/
