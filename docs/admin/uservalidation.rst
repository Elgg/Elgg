User validation
###############

Plugins can influence how users are validated before they can use the website. 

.. contents:: Contents
   :depth: 2
   :local:

Listing of unvalidated users
============================

In the Admin section of the website is a list of all unvalidated users. Some actions can be taken on the users, like delete them from the system
or validate them. 

Plugins have the option to add additional features to this list.

.. seealso::

	An example of this is the :doc:`/plugins/uservalidationbyemail` plugin which doesn't allow users onto the website until their e-mail address 
	is validated.

Require admin validation
========================

In the Site settings under the Users section there is a setting which can be enabled to require admin validation of a new user account before
the user can use their account. After registration the user gets notified that their account is awaiting validation by an administrator.

Site administrators can receive an e-mail notification that there are users awaiting validation.

After validation the user is notified that they can use their account.
