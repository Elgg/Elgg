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

Exposing methods
----------------

The function to use to expose a method is ``elgg_ws_expose_function()``. As an
example, let's assume you want to expose a function that echos text back
to the calling application. The function could look like this

.. code:: php

    function my_echo($string) {
        return $string;
    }

Since we are providing this function to allow developers to test their
API clients, we will require neither API authentication nor user
authentication. This call registers the function with the web services
API framework:

.. code:: php

    elgg_ws_expose_function("test.echo",
                    "my_echo",
                     array("string" => array('type' => 'string')),
                     'A testing method which echos back a string',
                     'GET',
                     false,
                     false
                    );

If you add this code to a plugin and then go to
http://yoursite.com/services/api/rest/xml/?method=system.api.list, you
should now see your test.echo method listed as an API call. Further, to
test the exposed method from a web browser, you could hit the url:
http://yoursite.com/services/api/rest/xml/?method=test.echo&string=testing
and you should see xml data like this:

.. code:: xml

    <elgg>
        <status>0</status>
        <result>testing</result>
    </elgg>

Response formats
~~~~~~~~~~~~~~~~

The web services API framework provides three different response formats
by default: xml, json, and serialized php. You can request the different
formats for substituting “json” or “php” for “xml” in the above URLs.
You can also add additional response formats by defining new viewtypes.

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
that have viewed the site in the last x minutes.

.. code:: php

    function count_active_users($minutes=10) {
        $seconds = 60 * $minutes;
        $count = count(find_active_users($seconds, 9999));
        return $count;
    }

Now, let's expose it and make the number of minutes an optional
parameter:

.. code:: php

    elgg_ws_expose_function("users.active",
                    "count_active_users",
                     array("minutes" => array('type' => 'int',
                                              'required' => false)),
                     'Number of users who have used the site in the past x minutes',
                     'GET',
                     true,
                     false
                    );

This function is now available and if you check ``system.api.list``, you
will see that it requires API authentication. If you hit the method with
a web browser, it will return an error message about failing the API
authentication. To test this method, you need an API key. Fortunately,
there is a plugin called apiadmin that creates keys for you. It is
available in the Elgg plugin repository. It will return a public and
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

OAuth
~~~~~

With the addition of the OAuth plugin, Elgg also fully supports the
OAuth 1.0a authorization standard. Clients can then use standard OAuth
libraries to make any API calls to the site.

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

.. code:: php

    function my_post_to_wire($text) {
        
        $text = substr($text, 0, 140);

        $access = ACCESS_PUBLIC;
       
        // returns guid of wire post
        return thewire_save_post($text, $access, "api");
    }

Exposing this function is the same as the previous except we require
user authentication and we're going to make this use POST rather than
GET HTTP requests.

.. code:: php

    elgg_ws_expose_function("thewire.post",
                    "my_post_to_wire",
                     array("text" => array('type' => 'string')),
                     'Post to the wire. 140 characters or less',
                     'POST',
                     true,
                     true
                    );

Please note that you will not be able to test this using a web browser
as you did with the other methods. You need to write some client code to
do this. There is some example client code in ``/engine/lib/api.php``.
Take a look at `send\_api\_post\_call()`_. You can also do a search for
clients that have been written for the APIs of Flickr or Twitter or any
other similar API. You will find a wide variety written in almost any
language you can think of.

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

.. _send\_api\_post\_call(): http://reference.elgg.org/lib_2api_8php.html#ee7382c2cbf1ad49ac6892556d3eaff2

Determining the authentication available
----------------------------------------

Elgg's web services API uses a type of `pluggable authentication module
(PAM)`_ architecture to manage how users and developers are
authenticated. This provides you the flexibility to add and remove
authentication modules. Do you want to not use the default user
authentication PAM but would prefer using OAuth? You can do this.

The first step is registering a callback function for the *rest, init*
plugin hook:

.. code:: php

    register_plugin_hook('rest', 'init', 'rest_plugin_setup_pams');

Then in the callback function, you register the PAMs that you want to
use:

.. code:: php

    function rest_plugin_setup_pams() {
        // user token can also be used for user authentication
        register_pam_handler('pam_auth_usertoken');

        // simple API key check
        register_pam_handler('api_auth_key', "sufficient", "api");
            
        // override the default pams
        return true;
    }

When testing, you may find it useful to register the
``pam_auth_session`` PAM so that you can easily test your methods from
the browser. Be careful not to use this PAM on a production site because
it could open up your users to a `CSRF attack`_.

Right now, the only other PAMs publicly available besides those provided
by the Elgg core are the OAuth PAMs. See `Justin Richer's OAuth plugin`_
for more detail.

.. _pluggable authentication module (PAM): http://en.wikipedia.org/wiki/Pluggable_Authentication_Modules
.. _CSRF attack: http://en.wikipedia.org/wiki/Csrf
.. _Justin Richer's OAuth plugin: http://community.elgg.org/pg/plugins/jricher/read/385119/oauth

Related
-------

.. toctree::
   :maxdepth: 1
   
   web-services/hmac