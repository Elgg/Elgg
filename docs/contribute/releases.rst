Release Process Workflow
########################

Release a new version of Elgg.

This is the process the core team follows for making a new Elgg release.
We have published this information in the spirit of openness,
and to streamline onboarding of new team members.

.. contents:: Contents
   :local:
   :depth: 1

Requirements
============

* SSH access to elgg.org
* Commit access to http://github.com/Elgg/Elgg
* Admin access to https://elgg.org/
* Access to `Twitter account`_
* Node.js and NPM installed
* Sphinx installed (``easy_install sphinx && easy_install sphinx-intl``)
* Transifex client installed (``easy_install transifex-client``)
* Transifex account with access to Elgg project

Update composer dependencies
============================
Since Elgg 2.3, ``composer.lock`` is committed to the repository. Therefore,
if any of the composer dependencies require an update, run ``composer update``
on the corresponding branch and make a pull request with an updated ``composer.lock`` file.
This will run the test suite and ensure that new dependencies do not break the build.

Merge commits up from lower branches
====================================

Determine the LTS branch (currently 1.12). We need to merge any new commits there up through the other
branches.

For each branch
---------------

Check out the branch, make sure it's up to date, and make a new work branch with the merge. E.g. here we're
merging 1.12 commits into 2.0:

.. code:: sh

    git checkout 2.0
    git pull
    git checkout -b merge112
    git merge 1.12

.. note:: If already up-to-date (no commits to merge), we can stop here for this branch.

If there are conflicts, resolve them, ``git add .``, and ``git merge``.

Make a PR for the branch and wait for automated tests and approval by other dev(s).

.. code:: sh

    git push -u my_fork merge112

Once merged, we would repeat the process to merge 2.0 commits into 2.1.

First new stable minor/major release
====================================

Update the :doc:`/appendix/support` to include the new minor/major release date and fill in the blanks for the previous release.

Prepare the release
======================

Bring your local git clone up to date.

Merge latest commits up from lowest supported branch.

Visit ``https://github.com/Elgg/Elgg/compare/<new>...<old>`` and submit the PR if there is anything that needs to be merged up.

Install the prerequisites:

.. code:: sh

    npm install elgg-conventional-changelog
    easy_install sphinx
    easy_install sphinx-intl
    easy_install transifex-client

.. note:: On Windows you need to run these command in a console with admin privileges

Run the ``release.php`` script. For example, to release 1.12.5:

.. code:: sh

    git checkout 1.12
    php .scripts/release.php 1.12.5

This creates a ``release-1.12.5`` branch in your local repo.

Next, manually browse to the ``/admin/settings/basic`` page and verify it loads. If it does not, a language file from Transifex may have a PHP syntax error. Fix the error and amend your commit with the new file:

.. code:: sh

    # only necessary if you fixed a language file
    git add .
    git commit --amend

Next, submit a PR via GitHub for automated testing and approval by another developer:

.. code:: sh

    git push your-remote-fork release-1.12.5

Tag the release
===============

Once approved and merged, tag the release:

.. code:: sh

    git checkout release-${version}
    git tag -a ${version} -m'Elgg ${version}'
    git push --tags origin release-${version}

Or create a release on GitHub

* Goto releases
* Click 'Draft a new release'
* Enter the version
* Select the correct branch (eg 1.12 for a 1.12.x release, 2.3 for a 2.3.x release, etc)
* Set the release title as 'Elgg {version}'
* Paste the CHANGELOG.md part related to this release in the description

Some final administration

* Mark GitHub release milestones as completed
* Move unresolved tickets in released milestones to later milestones

Update the website
==================

* ssh to elgg.org
* Clone https://github.com/Elgg/elgg-scripts

Build zip package
-----------------

Use ``elgg-scripts/build/elgg-starter-project.sh`` to generate the .zip file. Run without arguments to see usage.

.. note::

	If this is your first time on the server building a release run ``composer global require "fxp/composer-asset-plugin:^1.2.0"``.
	This will make sure you can download bower-assets during the build process.

.. code:: sh

	# login as user deploy
	sudo -su deploy
	
    # regular release
    ./elgg-starter-project.sh master 2.0.4 /var/www/www.elgg.org/download/
	
    # MIT release
    ./elgg-starter-project.sh master 2.0.4-mit /var/www/www.elgg.org/download/


* Verify that ``vendor/elgg/elgg/composer.json`` in the zip file has the expected version.
* If not, make sure GitHub has the release tag, and that the starter project has a compatible ``elgg/elgg``
  item in the composer requires list.

Building 1.x zip packages
~~~~~~~~~~~~~~~~~~~~~~~~~

Use ``elgg-scripts/build/build.sh`` to generate the .zip file. Run without arguments to see usage.

.. code:: sh

    # regular release
    ./build.sh 1.12.5 1.12.5 /var/www/www.elgg.org/download/

    # MIT release
    ./build.sh 1.12.5 1.12.5-mit /var/www/www.elgg.org/download/

Update elgg.org download page
-----------------------------

* Clone https://github.com/Elgg/community
* Add the new version to ``classes/Elgg/Releases.php``
* Commit and push the changes
* Update the plugin on www.elgg.org

.. code:: sh

	composer update elgg/community

Update elgg.org
---------------

* Clone https://github.com/Elgg/www.elgg.org
* Change the required Elgg version in ``composer.json``
* Update vendors

.. code:: sh

    composer update

* Commit and push the changes
* Pull to live site

.. code:: sh

    cd /var/www/www.elgg.org && sudo su deploy && git pull
      
* Update dependencies

.. code:: sh

    composer install --no-dev --prefer-dist --optimize-autoloader

* Go to community admin panel
    * Flush APC cache
    * Run upgrade

Make the announcement
=====================

This should be the very last thing you do.

#. Open ``https://github.com/Elgg/Elgg/blob/<tag>/CHANGELOG.md`` and copy the contents for that version
#. Sign in at https://elgg.org/blog and compose a new blog with a summary
#. Copy in the CHANGELOG contents, clear formatting, and manually remove the SVG anchors
#. Add tags ``release`` and ``elgg2.x`` where x is whatever branch is being released
#. Tweet from the elgg `Twitter account`_

.. _Twitter account: https://twitter.com/elgg
