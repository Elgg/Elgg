API results
###########

.. contents:: Contents
   :local:
   :depth: 2

Success result structure
========================

A successful API result looks like this:

.. code-block:: json

	{
		"status": 0,
		"result": "API result"
	}

Depending on the API call ``result`` can contain any type of content (string, number, array, object, etc.). 	

An example of a numberic result (for example a user count):

.. code-block:: json

	{
		"status": 0,
		"result": 10
	}

An example of an object result (for example a user):

.. code-block:: json

	{
		"status": 0,
		"result": {
			"name": "Some user",
			"username": "apiexample",
			"email": "user@example.com"
		}
	}

Error result structure
======================

When an API call fails the result will look like this:

.. code-block:: json

	{
		"status": -1,
		"message": "The reason the API call failed"
	}


Default status codes
====================

The ``status`` field always contains a number representing the result. Any value other than ``0`` is considered an error.

- ``0``: This is a success result
- ``-1``: This is a generic error result
- ``-20``: The user authentication token is missing, is invalid or has expired
- ``-30``: The api key has been disabled
- ``-31``: The api key is inactive
- ``-32``: The api key is invalid

Developers can implement their own status codes to represent different error states, so the request doesn't have to rely 
on the error message to know what went wrong.

.. note::

	``result`` and ``message`` can contain messages in different languages. This is depending on the user language when using 
	user authenticated API calls or the site langauge for other API calls. Keep in mind that the language can change, eighter by the user
	or by a site administrator for the site language.
