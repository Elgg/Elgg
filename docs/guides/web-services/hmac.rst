HMAC Authentication
===================

Elgg's RESTful API framework provides functions to support a `HMAC`_ signature scheme for API authentication. The client must send 
the HMAC signature together with a set of special HTTP headers when making a call that requires API authentication. This ensures 
that the API call is being made from the stated client and that the data has not been tampered with.

.. _HMAC: https://en.wikipedia.org/wiki/HMAC

The HMAC must be constructed over the following data:

- The public API key identifying you to the Elgg api server as provided by the APIAdmin plugin
- The private API Key provided by Elgg (that is companion to the public key)
- The current unix time in seconds
- A nonce to guarantee two requests the same second have different signatures
- URL encoded string representation of any GET variable parameters, eg ``method=test.test&foo=bar``
- If you are sending post data, the hash of this data

Some extra information must be added to the HTTP header in order for this data to be correctly processed:

- **X-Elgg-apikey** - The public API key
- **X-Elgg-time** - Unix time used in the HMAC calculation
- **X-Elgg-nonce** - a random string
- **X-Elgg-hmac** - The HMAC as base64 encoded
- **X-Elgg-hmac-algo** - The algorithm used in the HMAC calculation

If you are sending POST data you must also send:

- **X-Elgg-posthash** - The hash of the POST data
- **X-Elgg-posthash-algo** - The algorithm used to produce the POST data hash
- **Content-type** - The content type of the data you are sending (if in doubt use ``application/octet-stream``)
- **Content-Length** - The length in bytes of your POST data

Elgg provides a sample API client that implements this HMAC signature: ``\Elgg\WebServices\ElggApiClient``. It serves as a good 
reference on how to implement it.

Supported hashing algorithms
----------------------------

- ``sha256``: recommended
- ``sha1``: fast however less secure
- ``md5``: weak and will be removed in the future

Post hash calculation
---------------------

The post hash needs to be calculated over all the post data using one of the supported hashing algorithms.
The result of the hashing needs to be reported in the ``X-Elgg-posthash`` header and the used hashing algorithm must be 
reported in the ``X-Elgg-posthash-algo`` header.

HMAC hash calculation
---------------------

The overall HMAC needs to be calculated over the following data (in order) using the API secret as the HMAC secret and with one
of the supported hashing algorithms:

1. a UNIX timestamp, report this timestamp in the ``X-Elgg-time`` header
2. a random string, report this string in the ``X-Elgg-nonce`` header
3. the public API key, report this API key in the ``X-Elgg-apikey`` header
4. the url query string (for example ``method=test.test&foo=bar``)
5. when the request is a POST add the ``posthash`` as reported in the ``X-Elgg-posthash`` header

The resulting string needs to be base64 encoded and then url encoded and be repoted in the ``X-Elgg-hmac`` header.
The used hashing algorithm needs to be reported in the ``X-Elgg-hmac-algo``.

Hashing cache
-------------

For security reasons each HMAC hash needs to be unique, all submitted hashes are stored for 25 hours to prevent reuse.
