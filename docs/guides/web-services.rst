Web services
############

Build an HTTP API for your site.

Elgg provides a powerful framework for building web services. This
allows developers to expose functionality to other web sites and desktop
applications along with doing integrations with third-party web
applications. While we call the API RESTful, it is actually a REST/RPC
hybrid similar to the APIs provided by sites like Flickr and Twitter.

To create an API for your Elgg site, you need to do 4 things:

-  enable the web services plugin
-  expose methods
-  setup API authentication
-  setup user authentication

Additionally, you may want to control what types of authentication are
available on your site. This will also be covered.

.. contents:: Contents
   :local:
   :depth: 2

Security
--------

It is crucial that the web services are consumed via secure protocols. Do not
enable web services if your site is not served via HTTPs. This is especially
important if you allow API key only authentication.

If you are using third-party tools that expose API methods, make sure to carry
out a thorough security audit. You may want to make sure that API authentication
is required for ALL methods, even if they require user authentication. Methods that
do not require API authentication can be easily abused to spam your site.

Ensure that the validity of API keys is limited and provide mechanisms for your
API clients to renew their keys.

Exposing methods
----------------

API methods can be exposed in one of two ways:
- using the ``web_services`` section in the ``elgg-plugin.php`` file of your plugin
- during the ``'register', 'api_methods'`` event

As an example, let's assume you want to expose a function that echos text back
to the calling application. The function could look like this

.. code-block:: php

    function my_echo($string) {
        return $string;
    }

Since we are providing this function to allow developers to test their
API clients, we will require neither API authentication nor user
authentication. This call registers the function with the web services
API framework:

.. code-block:: php

    // as part of the elgg-plugin.php
    'web_services' => [
        'test.echo' => [
            'GET' => [ // the HTTP call method (GET|POST)
                'callback' => 'my_echo', // required
                'description' => 'A testing method which echos back a string', // optional, the description of the API method
                'params' => [ // optional, input parameters for the API method
                    'string' => ['type' => 'string'],
                ],
                'require_api_auth' => false, // optional, requires API authentication (default: false)
                'require_user_auth' => false, // optional, requires User authentication (default: false)
                'associative' => false, // optional, provide the input params as an array to the callback function (default: false)
            ],
        ],
    ],

    // as part of the 'register', 'api_methods' event
    function my_plugin_event_handler(\Elgg\Event $event) {
        $results = $event->getValue();

        $results['test.echo']['GET'] = [
            'callback' => 'my_echo',
            'description' => 'A testing method which echos back a string',
            'params' => [
                'string' => ['type' => 'string'],
            ],
        ];

        return $results;
    }

.. note::

    If no ``description`` is provided in the API method definition the system will check for the existence of the language key
    ``web_services:api_methods:<method>:<http call method>:description``

If you add this code to a plugin and then go to
http://yoursite.com/services/api/rest/json/?method=system.api.list, you
should now see your ``test.echo`` method listed as an API call. Further, to
test the exposed method from a web browser, you could hit the url:
http://yoursite.com/services/api/rest/json/?method=test.echo&string=testing
and you should see JSON data like this:

.. code-block:: json

    {
    	"status":0,
    	"result":"testing"
    }

Plugins can filter the output of individual API methods by registering a handler
for ``'rest:output',$method`` event.

Response formats
~~~~~~~~~~~~~~~~

JSON is the default format, however XML and serialized PHP can be fetched by enabling the ``data_views``
plugin and substituting ``xml`` or ``php`` in place of ``json`` in the above URLs.

You can also add additional response formats by defining new view types.

Parameters
~~~~~~~~~~

Parameters expected by each method should be listed as an associative array, where the key represents the parameter name, and the value 
contains an array with ``type``, ``default`` and ``required`` fields.

Values submitted with the API request for each parameter should match the declared type. API will throw on exception if validation fails.

Recognized parameter types are:	

 - ``integer`` (or ``int``)
 - ``boolean`` (or ``bool``) ``'false'``, ``0`` and ``'0'`` will evaluate to ``false`` the rest will evaluate to ``true``
 - ``string``
 - ``float``
 - ``array``

Unrecognized types will throw an API exception.

You can use additional fields to describe your parameter, e.g. ``description``.

.. code-block:: php

    // as part of the elgg-plugin.php
    'web_services' => [
        'test.greet' => [
            'GET' => [
                'callback' => 'my_greeting',
                'description' => 'A testing method which greets the user with a custom greeting',
                'params' => [
                    'name' => [
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Name of the person to be greeted by the API',
                    ],
                    'greeting' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'Hello',
                        'description' => 'Greeting to be used, e.g. "Good day" or "Hi"',
                    ],
                ],
            ],
        ],
    ],

.. note::

	If a missing parameter has no default value, the argument will be ``null``. Before Elgg v2.1, a bug caused later
	arguments to be shifted left in this case.

Receive parameters as associative array
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you have a large number of method parameters, you can force the execution script
to invoke the callback function with a single argument that contains an associative
array of parameter => input pairs (instead of each parameter being a separate argument).
To do that, set ``$assoc`` to ``true`` in ``elgg_ws_expose_function()``.

.. code-block:: php

	function greet_me($values) {
		$name = elgg_extract('name', $values);
		$greeting = elgg_extract('greeting', $values, 'Hello');
		return "$greeting, $name";
	}

    // as part of the elgg-plugin.php
    'web_services' => [
        'test.greet' => [
            'GET' => [
                'callback' => 'greet_me',
                'description' => 'A testing method which echos a greeting',
                'params' => [
                    'name' => [
                        'type' => 'string',
                    ],
                    'greeting' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'Hello',
                    ],
                ],
                'associative' => true,
            ],
        ],
    ],

.. note::

    If a missing parameter has no default value, ``null`` will be used.

API authentication
------------------

You may want to control access to some of the functions that you expose.
Perhaps you are exposing functions in order to integrate Elgg with
another open source platform on the same server. In that case, you only
want to allow that other application access to these methods. Another
possibility is that you want to limit what external developers have
access to your API. Or maybe you want to limit how many calls a
developer can make against your API in a single day.

In all of these cases, you can use Elgg's API authentication functions
to control access. Elgg provides two built-in methods to perform API
authentication: key based and HMAC signature based. You can also add
your own authentication methods. The key based approach is very similar
to what Google, Flickr, or Twitter. Developers can request a key (a
random string) and pass that key with all calls that require API
authentication. The keys are stored in the database and if an API call
is made without a key or a bad key, the call is denied and an error
message is returned.

Key-based authentication
~~~~~~~~~~~~~~~~~~~~~~~~

As an example, let's write a function that returns the number of users
that have registered on your site since a certain timestamp.

.. code-block:: php

    function count_new_users(int $since) {
        return elgg_count_entities([
            'type' => 'user',
            'created_since' => $since,
        ]);
    }

Now, let's expose it and make the number of minutes a required parameter:

.. code-block:: php

	// as part of the elgg-plugin.php
    'web_services' => [
        'users.new' => [
            'GET' => [
                'callback' => 'count_new_users',
                'description' => 'Number of users who have used the site in the past x minutes',
                'params' => [
                   'since' => [
                        'type' => 'int',
                        'required' => true,
                    ],
                ],
                'require_api_auth' => true,
            ],
        ],
    ],

This function is now available and if you check ``system.api.list``, you
will see that it requires API authentication. If you hit the method with
a web browser, it will return an error message about failing the API
authentication. To test this method, you need an API key. As of Elgg 3.2 API 
keys can be generated by the webservices plugin. It will return a public and
private key and you will use the public key for this kind of API
authentication. Grab a key and then do a GET request with your browser
on this API method passing in the key string as the parameter
``api_key``. It might look something like this:
http://yoursite.com/services/api/rest/xml/?method=users.active&api_key=1140321cb56c71710c38feefdf72bc462938f59f.

Signature-based authentication
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The :doc:`web-services/hmac` is similar to what is used with OAuth or
Amazon's S3 service. This involves both the public and private key. If
you want to be very sure that the API calls are coming from the
developer you think they are coming from and you want to make sure the
data is not being tampered with during transmission, you would use this
authentication method. Be aware that it is much more involved and could
turn off developers when there are other sites out there with key-based
authentication.

User authentication
-------------------

So far you have been allowing developers to pull data out of your Elgg
site. Now we'll move on to pushing data into Elgg. In this case, it is
going to be done by a user. Maybe you have created a desktop application
that allows your Users to post to the wire without going to the site.
You need to expose a method for posting to the wire and you need to make
sure that a user cannot post using someone else's account. Elgg provides
a token-based approach for user authentication. It allows a user to
submit their username and password in exchange for a token using the
method ``auth.gettoken``. This token can then be used for some amount of
time to authenticate all calls to the API before it expires by passing
it as the parameter ``auth_token``. If you do not want to have your
users trusting their passwords to 3rd-party applications, you can also
extend the current capability to use an approach like OAuth.

Let's write our wire posting function:

.. code-block:: php

    function my_post_to_wire($text) {
        
        $text = elgg_substr($text, 0, 140);

        $access = ACCESS_PUBLIC;
       
        // returns guid of wire post
        return thewire_save_post($text, $access, "api");        
    }

Exposing this function is the same as the previous except we require
user authentication and we're going to make this use POST rather than
GET HTTP requests.

.. code-block:: php

	// as part of the elgg-plugin.php
    'web_services' => [
        'thewire.post' => [
            'POST' => [
                'callback' => 'my_post_to_wire',
                'description' => 'Post to the wire. 140 characters or less',
                'params' => [
                   'text' => [
                        'type' => 'string',
                    ],
                ],
                'require_api_auth' => true,
                'require_user_auth' => true,
            ],
        ],
    ],

Please note that you will not be able to test this using a web browser
as you did with the other methods. You need to write some client code to
do this.

Building out your API
---------------------

As soon as you feel comfortable with Elgg's web services API framework,
you will want to step back and design your API. What sort of data are
you trying to expose? Who or what will be API users? How do you want
them to get access to authentication keys? How are you going to document
your API? Be sure to take a look at the APIs created by popular Web 2.0
sites for inspiration. If you are looking for 3rd party developers to
build applications using your API, you will probably want to provide one
or more language-specific clients.

Determining the authentication available
----------------------------------------

Elgg's web services API uses a type of `pluggable authentication module
(PAM)`_ architecture to manage how users and developers are
authenticated. This provides you the flexibility to add and remove
authentication modules. Do you want to not use the default user
authentication PAM but would prefer using OAuth? You can do this.

The first step is registering a callback function for the ``'rest', 'init'`` event:

.. code-block:: php

    elgg_register_event_handler('rest', 'init', 'rest_plugin_setup_pams');

Then in the callback function, you register the PAMs that you want to
use:

.. code-block:: php

    function rest_plugin_setup_pams() {
        // user token can also be used for user authentication
        elgg_register_pam_handler(\Elgg\WebServices\PAM\UserToken::class);

        // simple API key check
        elgg_register_pam_handler(\Elgg\WebServices\PAM\APIKey::class, 'sufficient', 'api');
    }

.. _pluggable authentication module (PAM): http://en.wikipedia.org/wiki/Pluggable_Authentication_Modules
.. _CSRF attack: http://en.wikipedia.org/wiki/Csrf

Related
-------

.. toctree::
   :maxdepth: 1
   :glob:
   
   web-services/*
