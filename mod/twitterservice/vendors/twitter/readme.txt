Twitter for PHP (c) David Grudl, 2008 (http://davidgrudl.com)


Introduction
------------

Twitter for PHP is a very small and easy-to-use library for sending 
messages to Twitter and receiving status updates.


Project at GoogleCode: http://twitter-php.googlecode.com
Twitter's API documentation: http://groups.google.com/group/twitter-development-talk/web/api-documentation
My PHP blog: http://phpfashion.com


Requirements
------------
- PHP (version 5 or better)
- cURL extension


Usage
-----

Create object using your credentials (user name and password)

	$twitter = new Twitter($userName, $password);

The send() method updates your status. The message must be encoded in UTF-8:

	$twitter->send('I am fine today.');

The load() method returns the 20 most recent status updates 
posted in the last 24 hours by you and optionally by your friends:

	$withFriends = FALSE;
	$channel = $twitter->load($withFriends);

The returned channel is a SimpleXMLElement object. Extracting 
the information from the channel is easy:

	foreach ($channel->status as $status) {
		echo "message: ", $status->text;
		echo "posted at " , $status->created_at;
		echo "posted by " , $status->user->name;
	}


Files
-----
readme.txt        - This file.
license.txt       - The license for this software (New BSD License).
twitter.class.php - The core Twitter class source.
send.php          - Example sending message to Twitter.
load.php          - Example loading statuses from Twitter.
