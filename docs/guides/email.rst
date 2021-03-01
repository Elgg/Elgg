Email
#####

Elgg has the ability to send out emails. 
This can be done directly using functions like ``elgg_send_email()`` and ``notify_user()`` or indirectly through the notifications system. 
Below an overview of the feature of the email system.

.. contents:: Contents
   :local:
   :depth: 1
   
HTML Mail
=========

As an admin you can configure your site to have all outgoing emails to be HTML emails or just plain text emails. HTML emails are enabled by default.
When enabled the email contents will be wrapped in HTML elements and some CSS will be applied. This allows theme developers to style the emails.

The appropriate views to format and style the emails can be found in ``views/default/email``.

The CSS will be inlined automatically so it will work in most email clients. 
If your email contains images, those images can be converted to inline base64 encoded images (default) or attachments. 
Converted images are the best way to have images show consistently in various clients.

Instead of having the message converted automatically to a HTML, you can also provide your own ``html_message`` in the ``params`` of a notification.
The ``html_message`` can be either a ``Elgg\Email\HtmlPart`` or a ``string``. If it is a ``string`` Elgg will automatically try to inline provided CSS present in the ``css`` param.
If you do not want to inline CSS you will need to set the ``convert_css`` param to ``false``. Below an example of a custom HTML part.

.. code-block:: php

	elgg_send_email(\Elgg\Email::factory([
		'from' => 'from@elgg.org',
		'to' => 'to@elgg.org',
		'subject' => 'Test Email',
		'body' => 'Welcome to the site',
		'params' => [
			'html_message' => '
				<p>Welcome to the site</p>
				<img src="site_logo.png"/>
			',
			'convert_css' => true,
			'css' => 'p { padding: 10px;}'
		],
	]));


Attachments
===========

``notify_user()`` or enqueued notifications support attachments for e-mail notifications if provided in ``$params``. To add one or more attachments
add a key ``attachments`` in ``$params`` which is an array of the attachments. An attachment should be in one of the following formats:

- An ``ElggFile`` which points to an existing file
- An array with the file contents
- An array with a filepath

.. code-block:: php

	// this example is for notify_user()
	$params['attachments'] = [];

	// Example of an ElggFile attachment
	$file = new \ElggFile();
	$file->owner_guid = <some owner_guid>;
	$file->setFilename('<some filename>');

	$params['attachments'][] = $file;

	// Example of array with content  
	$params['attachments'][] = [
		'content' => 'The file content',
		'filename' => 'test_file.txt',
		'type' => 'text/plain',
	];

	// Example of array with filepath
	// 'filename' can be provided, if not basename() of filepath will be used
	// 'type' can be provided, if not will try a best guess
	$params['attachments'][] = [
		'filepath' => '<path to a valid file>',
	];

	notify_user($to_guid, $from_guid, $subject, $body, $params);


E-mail address formatting
=========================

Elgg has a helper class to aid in getting formatted e-mail addresses: ``\Elgg\Email\Address``.

.. code-block:: php

	// the constructor takes two variables
	// first is the email address, this is REQUIRED
	// second is the name, this is optional
	$address = new \Elgg\Email\Address('example@elgg.org', 'Example');
	
	// this will result in 'Example <example@elgg.org>'
	echo $address->toString();
	
	// to change the name use:
	$address->setName('New Example');
	
	// to change the e-mail address use:
	$address->setEmail('example2@elgg.org');

There are some helper functions available

- ``\Elgg\Email\Address::fromString($string)`` Will return an ``\Elgg\Email\Address`` class with e-mail and name set,
  provided a formatted string (eg. ``Example <example@elgg.org>``)
- ``\Elgg\Email\Address::fromEntity($entity)`` Will return an ``\Elgg\Email\Address`` class with e-mail and name set based on the entity
- ``\Elgg\Email\Address::getFormattedEmailAddress($email, $name)`` Will return a formatted string provided an e-mail address and optionaly a name
