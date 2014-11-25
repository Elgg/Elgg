HMAC Authentication
===================

Elgg's RESTful API framework provides functions to support a `HMAC`_ signature scheme for API authentication. The client must send the HMAC signature together with a set of special HTTP headers when making a call that requires API authentication. This ensures that the API call is being made from the stated client and that the data has not been tampered with.

.. _HMAC: http://en.wikipedia.org/wiki/HMAC

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
- **X-Elgg-none** - a random string
- **X-Elgg-hmac** - The HMAC as base64 encoded
- **X-Elgg-hmac-algo** - The algorithm used in the HMAC calculation - eg, sha1, md5 etc.

If you are sending POST data you must also send:

- **X-Elgg-posthash** - The hash of the POST data
- **X-Elgg-posthash-algo** - The algorithm used to produce the POST data hash - eg, md5
- **Content-type** - The content type of the data you are sending (if in doubt use application/octet-stream)
- **Content-Length** - The length in bytes of your POST data

Elgg provides a sample API client that implements this HMAC signature: send_api_call(). It serves as a good reference on how to implement it.