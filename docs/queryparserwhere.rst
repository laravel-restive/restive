Query Parser Filtering
======================

Simple Where Clauses
--------------------

Simple where clauses take the format of

::

    where[]=fieldname:operator:value

for example

::

    ?where[]=id:eq:1

would equate to an eloquent query of

::

    model::where('id', '=', 1)

The operators allowed are

- eq equates to =
- noteq equates to !=
- lte equates to <=
- gte equates to >=
- gt equates to >
- lt equates to <
- lk equates to LIKE
- nlk equates to NOT LIKE

Can also use orWhere

::

    orWhere[]=id:eq:1


Where In clauses
----------------

Where In clauses take the form of

::

    whereIn[]=fieldname:(comma separate list)

For example

::

    whereIn[]=id:(1,2,3)

Can also use

- orWhereIn
- whereNotIn
- orWhereNotIn


Where Between Clauses
---------------------

Where Between clauses take the form of

::

    whereBetween[]=fieldname:start:end

For example

::

    whereBetween[]=age:18:45

Can also use

- orWhereBetween
- whereNotBetween
- orWhereNotBetween
