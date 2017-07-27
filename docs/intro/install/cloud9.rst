:orphan:

Installing Elgg on Cloud9 IDE
#############################

1. Create a c9 workspace
========================

-  Go to http://c9.io
-  Login with GitHub
-  On the Dashboard, click "Create new workspace" => "Create a new
   workspace"
-  Choose a project name (e.g. "elgg")
-  Choose "PHP" for project type
-  Click "Create"
-  Wait... (~1 min for c9 workspace to be ready)
-  Click "Start editing" for the workspace

2. Set up the workspace for Elgg
================================

Run the following in cloud9's terminal:

.. code:: sh

    rm -rf * # Clear out the c9 hello-world stuff
    composer create-project elgg/elgg:~3.0 . # the hotness
    cp install/config/htaccess.dist .htaccess
    cp elgg-config/.env.php.example elgg-config/.env.php
    mysql-ctl start # start c9's local mysql server
    mkdir ../elgg-data # setup data dir for Elgg

Configure ``.env.php`` to be like so:

.. code::

	# Must set timezone explicitly!
    ELGG_DEFAULT_TZ="{{timezone}}"

    ELGG_DATAROOT="path/to/elgg-data"
    ELGG_WWWROOT="https://{workspace}-{username}.c9.io/"

    ELGG_DBUSER="${C9_USER}"
    ELGG_DBPASS=""
    ELGG_DBNAME="c9"
    ELGG_DBHOST="${IP}"
    ELGG_DBPREFIX="elgg_"


3. Complete the install process from Elgg's UI
==============================================

-  Hit "Run" at the top of the page to start Apache.
-  Go to ``https://{workspace}-{username}.c9.io/install.php?step=database``
-  Change Site URL to ``http://your-workspace.your-username.c9.io/``
-  Put in the data directory path. Should be something like
   ``/var/..../app-root/data/elgg-data/``.
-  Click "Next"
-  Create the admin account
-  Click "Go to site"
-  You may have to manually visit ``https://{workspace}-{username}.c9.io/``
   and login with the admin credentials you just configured.