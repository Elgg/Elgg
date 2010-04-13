Site pages - Quickly generate static pages and customize the front page, CSS, and
HTML metatags.

CONTENTS:
	1.  Overview
	2.  Using ECML on the front pages
	2.1  [[login_box]]
	2.2  [[site_stats]]
	2.3  [[user_list]]


1.  OVERVIEW

	Site Pages provides a simple way to create static content for About, Terms,
	and Privacy pages, and also allows simple modifications of the logged in
	and logged out views, as well as CSS and meta description and tags for SEO.


2.  USING ECML ON THE FRONT PAGES

	Site Pages supports ECML on the front page and provides the following
	ECML keywords:

	[[login_box]] -- A login box.  Required on the logged out front page.
	[[site_stats]] -- Simple site statistics.
	[[user_list]] -- A list of users.

	To learn more about ECML, click the ECML icon below the input fields.


2.1  [[login_box]]
	The Login Box keyword displays a box to let users log in.  This view
	is required on the logged out front page.


2.2  [[site_stats]]
	The Site Stats keyword doesn't do anything yet.


2.3  [user_list]]
	The User List keyword displays a list of users and takes the following
	optional arguments (*default if not passed):

	list_type=*new|online|random -- Show new users, users active in the
		last 10 minutes, or a random selection.

	only_with_avatars=*true|false -- Only show users who have uploaded an
		avatar

	limit=*10 -- Show this many users.