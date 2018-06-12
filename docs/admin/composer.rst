Composer installation
#####################

The easiest way to keep your Elgg site up-to-date is by using `Composer`_. Composer will take care of installing all the required dependencies 
of all plugins and Elgg, while also keeping those depencies up-to-date without having conflicts. 

.. contents:: Contents
   :depth: 1
   :local:

Install Composer
================

https://getcomposer.org/download/

Install Elgg as a Composer Project
==================================

.. code-block:: sh

	composer self-update
	composer create-project elgg/starter-project:dev-master ./path/to/my/project
	cd ./path/to/my/project
	composer install

This will create a composer.json file based of the `Elgg starter project`_ which has the basics of installing Elgg.

Open your browser
-----------------

Go to your browser and install Elgg via the installation interface

Setup version controls
======================

This step is optional but highly recommended. It'll allow you to easily manage the installation of the same plugin versions between environments 
(development/testing/production).

.. code-block:: sh

	cd ./path/to/my/project
	git init
	git add .
	git commit -a -m 'Initial commit'
	git remote add origin <git repository url>
	git push -u origin master


Install plugins
===============

Install plugins as Composer depencies. This assumes that a plugin has been registered on `Packagist`_

.. code-block:: sh

	composer require hypejunction/hypefeed
	composer require hypejunction/hypeinteractions
	# whatever else you need

Commit
======

Make sure ``composer.lock`` is not ignored in ``.gitignore``

.. code-block:: sh

	git add .
	git commit -a -m 'Add new plugins'
	git push origin master

Deploy to production
====================

Initial Deploy
--------------

.. code-block:: sh

	cd ./path/to/www
	
	# you can also use git clone
	git init
	git remote add origin <git repository url>
	git pull origin master
	
	composer install

Subsequent Deploys
------------------

.. code-block:: sh

	cd ./path/to/www
	git pull origin master
	
	# never run composer update in production
	composer install

.. _Composer: https://getcomposer.org/
.. _Packagist: https://packagist.org/
.. _Elgg starter project: https://github.com/Elgg/starter-project
