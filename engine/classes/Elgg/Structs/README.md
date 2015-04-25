Elgg Data Structures API
========================

A library of common data structure interfaces (mostly collections).

Collections differ from PHP's native array in several ways. By default:

 * They aren't mutable/writable (!!)
 * They don't have keys
 * They don't ever contain null items or return null values
 * The items don't have stable indexes

However, subinterfaces may augment the base Collection interface to add one or
more of the above features, as well as:

 * Sorting, such that iteration passes over items in a consistent order
   regardless of what order the items are inserted). Items in such a collection
   cannot be rearranged.

The data structures APIs are designed with the following principles:

 * Exceptions are bad. Instead of throwing for unsupported operations,
   interfaces should specify only the methods that will actually be
   implemented. This results in more interfaces, but also more clarity.

 * Null is worse. Throw meaningful exceptions or return empty values
   instead of returning null. This helps catch errors earlier and minimizes
   null checks which just add complexity without benefit.

 * Read-only is good. Performance improvements can be made more aggressively
   when we can guarantee the value won't be modified by clients.

 * Immutable is better. When values can't be modified at their source,
   clients can make even more performance improvements.

The read-only-by-default philosophy provides some nice guarantees that can be
harnessed for performance-related things like caching and lazy evaluation.

TODO(ewinslow): If PHP had generics support, we'd add that here.

DO NOT EXTEND OR IMPLEMENT this interface outside of this package.
Doing so would cause additions to the API to be breaking changes, which is
not what we want. You have a couple of options for how to proceed:
 * File a feature request
 * Submit a PR
 * Use composition -- http://en.wikipedia.org/wiki/Composition_over_inheritance


The important part of some interfaces is the tests that come along with them.
Any implementation must also run those tests to check for correctness,
otherwise the interface is just a hint at an implementation detail that
cannot be otherwise enforced by the type system.