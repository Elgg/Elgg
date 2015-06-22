Elgg Caching API
================

This document is primarily about the `Pool` interface and implementations.

Why do we need our own caching interface instead of just deferring entirely
to a third party like `Stash` or implementing the up-and-coming caching PSR?
I (Evan) have two reasons for this:

- Frees us from committing to the API of our dependencies.
- We can do better than what everyone else is doing :)

Typical interaction with a cache:

```php
$info = $cache->get($id);

// Check to see if the cache missed
// It either didn't exist or was stale.
if ($info == null) {
  $info = loadInfoFromDatabase($id);

  $cache->set($id, $info);
}

return $info;
```

There are three things you have to get right every time you use the cache:

- Check for misses (if-statement)
- Prime the cache ($cache->set)
- Call `lock` to prevent cache stampedes (not shown)

I was pondering if there was away to get around remembering and writing that 
logic every time. I think the solution is closures, which would allow us to do
this:

```php
$result = $cache->get($id, function() uses ($id) {
  // Only executed on miss; also stores the result
  return loadInfoFromDatabase($id);
});
```

We then provide a couple other typical-use methods for managing the cache.
This closure approach allows us to remove good chunk of complexity from the
`Pool` interface, streamlining both use and maintenance.

Under the hood we just defer to Stash for the implementation details, but
dependent classes don't need to know this, since they just depend on the `Pool`
interface.