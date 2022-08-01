Release Process Workflow
########################

Release a new version of Elgg.

This is the process the core team follows for making a new Elgg release.
We have published this information in the spirit of openness,
and to streamline onboarding of new team members.

.. contents:: Contents
   :local:
   :depth: 2

Requirements
============

* SSH access to elgg.org
* Commit access to http://github.com/Elgg/Elgg
* Admin access to https://elgg.org/
* Access to `Twitter account`_
* Node.js and Yarn installed
* Sphinx installed (``easy_install sphinx && easy_install sphinx-intl``)
* Transifex client installed (``easy_install transifex-client``)
* Transifex account with access to Elgg project
* Admin access to `Read The Docs`_
* Admin access to `Scrutinizer`_

Merge commits up from lower branches
====================================

Determine the LTS branch. We need to merge any new commits there up through the other branches.

For each branch
---------------

Check out the branch, make sure it's up to date, and make a new work branch with the merge. E.g. here we're
merging 1.12 commits into 2.0:

.. code-block:: sh

    git checkout 2.0
    git pull
    git checkout -b merge112
    git merge 1.12

.. note:: If already up-to-date (no commits to merge), we can stop here for this branch.

If there are conflicts, resolve them, ``git add .``, and ``git merge``.

Make a PR for the branch and wait for automated tests and approval by other dev(s).

.. code-block:: sh

    git push -u my_fork merge112

Once merged, we would repeat the process to merge 2.0 commits into 2.1.

Preparation for first new stable minor/major release
====================================================

* Update the :doc:`/appendix/support` to include the new minor/major release date and fill in the blanks for the previous release.
* Update the README.md file badges to point to the correct new release numbers.

Preparation for a new major release
-----------------------------------

* Change the Transifex configuration to push translations to a different project

Prepare the release
===================

Make a PR with translation updates
----------------------------------

Install the prerequisites:

.. code-block:: sh

    easy_install transifex-client
    easy_install sphinx
    easy_install sphinx-intl

.. note:: 
	
	On Windows you need to run these command in a console with admin privileges

Run the ``languages.php`` script. For example, to pull translations:

.. code-block:: sh

    php .scripts/languages.php 3.x

Make a pull request with the new translations and have it merged before the next step.

Next, manually browse to the ``/admin/site_settings`` page and verify it loads. If it does not, a language file from Transifex may 
have a PHP syntax error. Fix the error and amend your commit with the new file:

.. code-block:: sh

    # only necessary if you fixed a language file
    git add .
    git commit --amend

Make the release PR
-------------------

Bring your local git clone up to date.

Merge latest commits up from lowest supported branch.

Visit ``https://github.com/Elgg/Elgg/compare/<new>...<old>`` and submit the PR if there is anything that needs to be merged up.

Install the prerequisites:

.. code-block:: sh

    yarn install elgg-conventional-changelog

.. note:: 

	On Windows you need to run these command in a console with admin privileges

Run the ``release.php`` script. For example, to release 1.12.5:

.. code-block:: sh

    git checkout 1.12
    php .scripts/release.php 1.12.5

This creates a ``release-1.12.5`` branch in your local repo.

Next, submit a pull request via GitHub for automated testing and approval by another developer:

.. code-block:: sh

    git push your-remote-fork release-1.12.5

Tag the release
===============

Once approved and merged, create a release on GitHub:

* Goto releases
* Click 'Draft a new release'
* Enter the version
* Select the correct branch (eg 1.12 for a 1.12.x release, 2.3 for a 2.3.x release, etc)
* Set the release title as 'Elgg {version}'
* Paste the CHANGELOG.md part related to this release in the description

.. note::

	GitHub is setup to listen to the creation of a new release to automaticly make the ZIP release of Elgg.
	After the release was created wait a few minutes and the ZIP should be added to the release.

Some final administration

* Mark GitHub release milestones as completed
* Move unresolved tickets in released milestones to later milestones

Additional actions for the first new minor / major
--------------------------------------------------

* Make a new branch on GitHub (for example 3.3)
* Set the new branch as the default branch (optional, but suggested for stable releases)
* Configure `Read The Docs`_ to build the new branch (not the new tag)
* Configure `Scrutinizer`_ to build the new branch
* Check the Elgg starter project for potential requirement / config changes in the ``composer.json``
* Add the new minor / major version to the ``Elgg/community_plugins`` repository so developers can upload plugins for the new release
* Update the build configuration for the `Elgg reference`_ (on the Elgg.org webserver)

.. code-block:: sh

	# in the file /root/elgg-scripts/cron/make_reference
	# set the main build branch to the correct branch
	# make sure if you change the main build branch to add the previous branch to the other branches to build
	# the new configuration will be applied by the daily cron

Additional action for the first new major
-----------------------------------------

* On GitHub add a branch protection rule (for example ``4.*``) 
* Configure Scrutinizer to track the new major branches (for example ``4.*``)

Update the website
==================

Update elgg.org download page
-----------------------------

* Clone https://github.com/Elgg/community
* Add the new version to ``classes/Elgg/Releases.php``
* Commit and push the changes
* Download the ZIP release from GitHub
* Upload the ZIP to the elgg.org webserver

.. code-block:: sh

	sudo mv ~/elgg-x.y.z.zip /var/www/www.elgg.org/download
	sudo chown deploy:deploy /var/www/www.elgg.org/download/elgg-x.y.z.zip

Update elgg.org
---------------

* Clone https://github.com/Elgg/www.elgg.org
* (optional) Change the required Elgg version in ``composer.json``
* Update vendors

.. code-block:: sh

    composer update

* Commit and push the changes
* Pull to live site

.. code-block:: sh

    sudo -su deploy 
    cd /var/www/www.elgg.org 
    git pull
    composer install --no-dev

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
.. _Read The Docs: https://readthedocs.org/projects/elgg/
.. _Scrutinizer: https://scrutinizer-ci.com/g/Elgg/Elgg/
.. _Elgg reference: http://reference.elgg.org/
