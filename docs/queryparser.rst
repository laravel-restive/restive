Query Parser
============

The query parser allows for complex filtering, sorting, the use of child relations and more.

Currently the filter parser supports

- with
- where
- orWhere
- whereIn
- orWhereIn
- whereNotIn
- orWhereNotIn
- whereBetween
- whereNotBetween
- orWhereBetween
- orWhereNotBetween
- withTrashed
- onlyTrashed
- scope

Sorting allows for multiple sort targets for ascending and descending sorts.

Includes allow for loading child models.

Joins are also supported

Query results by default return all columns for the query, however you can use the columns filter to restrict which
columns are returned.



.. toctree::
    :maxdepth: 2
    :hidden:

    queryparserwhere
    queryparsersoftdeletes
    queryparsersorting
    queryparsercolumns
    queryparserwith
    queryparserjoins
    queryparserscopes


URL Parameter Format
--------------------
For ``GET`` e.g. ``index`` routes then the parser parameters can be placed in the url.

for example.

``{api-uri}?columns[]=id,name&where[]=id:eq:1&orWhereBetween[]=age:(10,15)&orWhereBetween[]=age:(50,60)``


For ``DELETE`` and ``PUT`` the parser parameters are added to the body of the normal request, inside a parameter
called ``@parser``

For example

.. code-block:: php

        $response = $this->put("/user", [
            'email' => 'dirk2@holisticdetective.com',
            'name' => 'Dirk Gently',
            'age' => 45,
            '@parser' => ['where' => ['email:eq:dirk@holisticdetective.com']]
        ]);

