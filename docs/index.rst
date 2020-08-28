Introduction
============

Restive is written for Laravel and provides a query parser and api controllers for CRUD type actions.

The query parser allows for complex filtering and sorting, converting the URI query into eloquent queries.

The API controller supports resource creation, reading, updating and deletion.

Reading, updating and deletion can all access the query parser.

e.g.

::

   whereIn
   whereBetween
   sort

although note that the filtering can be more complex than just a simple ``where``

.. toctree::
    :maxdepth: 2
    :hidden:

    installation
    queryparser
    apicontrollers
    authorization
    testing
    contributing
    acknowledgements
    license
    todo
