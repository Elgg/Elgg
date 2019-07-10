Adding a Service to Elgg
########################

The :doc:`services guide </guides/services>` has general information about using Elgg services.

To add a new service object to Elgg:

#. Annotate your class as ``@internal``.
#. Open the class ``Elgg\Di\ServiceProvider``.
#. Add a ``@property-read`` annotation for your service at the top. This allows IDEs and static code
   analyzers to understand the type of the property.
#. To the constructor, add code to tell the service provider what to return. See the class
   ``Elgg\Di\DiContainer`` for more information on how Elgg's DI container works.

At this point your service will be available from the service provider object, but will not yet be accessible to plugins.

Inject your dependencies
------------------------

Design your class constructor to *ask for* the necessary dependencies rather than creating them or using
``_elgg_services()``. The service provider's ``setFactory()`` method provides access to the service provider
instance in your factory method.

Here's an example of a ``foo`` service factory, injecting the ``config`` and ``db`` services into the constructor:

.. code-block:: php

    // in Elgg\Di\ServiceProvider::__construct()

    $this->setFactory('foo', function (ServiceProvider $c) {
        return new Elgg\FooService($c->config, $c->db);
    });

The full list of internal services can be seen in the ``@property-read`` declarations at the top
of ``Elgg\Di\ServiceProvider``.

.. warning::

    Avoid performing work in your service constructor, particularly if it requires database queries.
    Currently PHPUnit tests cannot perform them.


Making a service part of the public API
---------------------------------------

If your service is meant for use by plugin developers:

#. Make an interface ``Elgg\Services\<Name>`` that contains only those methods needed in the public API.
#. Have your service class implement that interface.
#. For methods that are in the interface, move the documentation to the interface. You can simply use
   ``{@inheritdoc}`` in the PHPDocs of the concrete class methods.
#. Document your service in ``docs/guides/services.rst`` (this file).
#. Open the PHPUnit test ``Elgg\ApplicationTest`` and add your service key to the ``$names`` array
   in ``testServices()``.
#. Open the class ``Elgg\Application``.
#. Add ``@property-read`` declaration to document your service, but use your **interface** as the type,
   *not* your service class name.
#. Add your service key to the array in the ``$public_services`` property, e.g. ``'foo' => true,``

Now your service will be available via property access on the ``Elgg\Application`` instance:

.. code-block:: php

    // using the public foo service
    $three = elgg()->foo->add(1, 2);

.. note::

    For examples, see the ``config`` service, including the interface ``Elgg\Services\Config``
    and the concrete implementation ``Elgg\Config``.

Service Life Cycle and Factories
================================

By default, services registered on the service provider are "shared", meaning the service provider
will store the created instance for the rest of the request, and serve that same instance to all
who request the property.

If you need developers to be able to construct objects that are pre-wired to Elgg services, you may
need to add a public factory method to ``Elgg\Application``. Here's an example that returns a new
instance using internal Elgg services:

.. code-block:: php

    public function createFoo($bar) {
        $logger = $this->services->logger;
        $db = $this->services->db;
        return new Elgg\Foo($bar, $logger, $db);
    }
