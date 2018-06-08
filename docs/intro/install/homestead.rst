:orphan:

Installing Elgg on Homestead
############################

`Homestead`_ is an excellent Vagrant box tailored for PHP development made by the developers of `Laravel`_.
It allows you to set up an Ubuntu virtual machine in a matter of minutes, saving time installing and configuring an Apache server and all the other tools necessary for local development and testing.

.. _Homestead: https://laravel.com/docs/5.4/homestead
.. _Laravel: https://laravel.com

1. Install Homestead
====================

Install one of the preferred virtual machine providers, Vagrant and Homestead following the instructions at https://laravel.com/docs/5.4/homestead

If you have the virtual machine and Vagrant installed, you can use the following commands

.. code-block:: sh

    vagrant box add laravel/homestead

    # navigate to the directory that will hold your Homestead installation, e.g. your home directory
    cd ~
    git clone https://github.com/laravel/homestead.git Homestead
    cd Homestead
    # checkout the latest stable release
    git checkout <tagged release version>
    bash init.sh

.. warning:: On Windows, make sure to run your command line tool as an Administrator.


2. Configure Homestead
======================

Edit Homestead.yaml to include the details of your new Elgg project(s).
In the following example, we will set up two Elgg apps - one from git source and one using the starter project.
Note that when configuring sites, you can use the "elgg" site type, which will automatically bootstrap nginx vhosts, using the `shell config script`_

.. code-block:: yaml

    ---
    ip: "192.168.10.10"
    memory: 2048
    cpus: 1
    provider: virtualbox

    authorize: ~/.ssh/id_rsa.pub

    keys:
        - ~/.ssh/id_rsa

    folders:
        - map: ~/apps/elgg-starter
          to: /home/vagrant/Code/elgg-starter
          type: "nfs"

        - map: ~/apps/elgg-git
          to: /home/vagrant/Code/elgg-git
          type: "nfs"

    sites:
        - map: elgg-starter.app
          to: /home/vagrant/Code/elgg-starter/public
          type: elgg

        - map: elgg-git.app
          to: /home/vagrant/Code/elgg-git/public
          type: elgg

    databases:
        - elgg-sandbox
        - elgg-git


`NFS`_ is not supported on Windows, but you can try `WinNFSd`_ plugin

.. _NFS:  https://www.vagrantup.com/docs/synced-folders/nfs.html
.. _WinNFSd: https://github.com/winnfsd/vagrant-winnfsd
.. _shell config script: https://github.com/laravel/homestead/blob/master/scripts/serve-elgg.sh


3. Update hosts
===============

Update your hosts file to point domains configured in Homestead to the Vagrant box IP address.
This will allow yout to access your sites by domain name from the browser

.. code-block:: text

    192.168.10.10 elgg-starter.app
    192.168.10.10 elgg-git.app


4.a Install Elgg using starter-project
======================================

.. code-block:: sh

    # create the directory to hold the project on your local machine
    cd ~/apps
    mkdir elgg-starter

    # head to your Homestead installation directory
    cd ~/Homestead

    # launch the Vagrant box
    # this will automatically create all the project directories,
    # setup vhosts and create the databases
    vagrant up

    # SSH into your Vagrant box
    vagrant ssh

    # you can use the cli tool to also install Elgg without leaving the console
    # you can skip this if you want to install Elgg in your browser
    composer global require hypejunction/elgg-cli

    # new project folder should have automatically created during vagrant up
    # this directory should be in full sync with your local machine
    cd /home/vagrant/Code/elgg-starter

    # create the data directory that will hold Elgg's cache and uploaded files
    # when prompted for dataroot during installation, you should set it to /home/vagrant/Code/elgg-starter/data/
    mkdir data

    # create a new project from Elgg's starter project
    # watch out for messages, you may need to add your github token here
    # when prompted for installation root during installation, you should set it to /home/vagrant/Code/elgg-starter/public/
    composer create project elgg/starter-project:dev-master public

    # install composer dependencies
    cd public

    # run composer install twice! don't ask why
    composer install
    composer install

    # now if you head to your browser at http://elgg-starter.app/ you should should be able to install Elgg
    # using the installation interface
    # alternatively, use the cli tool we have required previously, and follow the prompts
    # note that the default "root" user password for most services on the Homestead box is "secret",
    # DB name is "elgg-starter" as seen in Homestead config
    elgg-cli install

    # run some tests
    vendor/bin/phpunit

    # if you are planning to use this project for development, you can commit it to git
    git init
    git add .
    git commit -a -m 'Base starter project'
    git remote add origin git@github.com:name/project.git
    git push -u origin master

    composer require elgg/mentions

    git add .
    git commit -a -m 'Added mentions plugin'
    git push origin master

    # you can then open the project on your local machine, make changes using an editor, and commit via this console
    # this saves you the trouble of installing composer, git et al locally

    # to end the ssh session with the box
    exit

    # after finishing work with the box, you can choose to suspend, halt or destroy it
    # https://www.vagrantup.com/intro/getting-started/teardown.html
    # destroying the box will wipe the databases, so if you plan to continue using the
    # installation, you may want to just halt the box


4.b Install Elgg from source
============================

Now we can install our second git project, which we can use to contribute code back to core.

.. code-block:: sh

    # create the directory to hold the project on your local machine
    cd ~/apps
    mkdir elgg-git

    # head to your Homestead installation directory
    cd ~/Homestead

    # we already a vagrant box running, so we need to provision it for the changes to take effect
    # in this particular case, we have added a local directory, which will need to be mounted and
    # mapped to the directory on the box
    vagrant reload --provision

    # SSH into your Vagrant box
    vagrant ssh

    cd /home/vagrant/Code/elgg-git

    # create the data directory that will hold Elgg's cache and uploaded files
    # when prompted for dataroot during installation, you should set it to /home/vagrant/Code/elgg-git/data
    mkdir data

    # when prompted for installation root during installation, you should set it to /home/vagrant/Code/elgg-git/public
    # fork Elgg/Elgg on github and clone your fork
    git clone https://github.com/mygitname/Elgg.git public

    # install composer dependencies
    cd public
    composer install

    # now if you head to your browser at http://elgg-git.app/ you should should be able to install Elgg
    # using the installation interface
    # alternatively, use the cli tool we have required previously, and follow the prompts
    # note that the default root password for most services on the Homestead box is "secret"
    elgg-cli install

    # add upstream to original Elgg repository, so we can later make pull requests
    git remote add upstream https://github.com/Elgg/Elgg.git

    # create a new branch
    git branch my-fix

    # add your fixes using an editor on the local machine
    # test your changes by visiting http://elgg-git.app/
    # run automated tests
    # commit and push your changes
    vendor/bin/phpunit
    git add .
    git commit -a -m 'fix(component): describe the fix'

    git push origin my-fix

    # rebase against upstream if your branch has diverged or you need to squash/edit commits
    git fetch upstream
    git rebase -i upstream/master
    git push --force origin my-fix

    exit


5. Other
========

.. code-block:: sh

    cd ~/Homestead
    vagrant ssh


    # setup cache symlink for improved performance
    cd /home/vagrant/Code/elgg-starter/public
    ln -l /home/vagrant/Code/elgg-starter/data/views_simplecache/ cache

    # you should see the symlink if you do
    ls -l


    # setup cron jobs
    crontab -e
    # add the following lines and save
    # * * * * * /usr/bin/wget -q http://elgg-starter.app/cron/run/ --spider
    # verify that that crontab is set / you can also check Admin > Statistics > Cron to see if the cron is running
    crontab -l


    # start memcached
    memcached -d start


    # backup the database
    cd /home/vagrant/Code/elgg-starter/
    mkdir backups
    mysqldump -u root -psecret elgg-starter > backups/elgg-starter.sql


    # restore the database
    mysql -u root -psecret elgg-starter < backups/elgg-starter.sql

