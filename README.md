cool-db-smf
===========

new db style for smf 2.xx

## Example ##

**old style**
```php
global $smcFunc;

$request = $smcFunc['db_query']('', '
    SELECT id_cat, cat_order
    FROM {db_prefix}categories',
    []
);
if ($smcFunc['db_num_rows']($request) == 0)
    return [];
while ($row = $smcFunc['db_fetch_assoc']($request))
{
    # ...
    $cat_order[$row['id_cat']] = $row['cat_order'];
}
$smcFunc['db_free_result']($request);
```

**new style**
```php
use Inter\DB;

$request = DB::query('
    SELECT id_cat, cat_order
    FROM {db_prefix}categories',
    []
);
if (!$request->num_rows)
    return [];
while ($row = $request->fetch_assoc)
{
    # ...
    $cat_order[$row['id_cat']] = $row['cat_order'];
}
$request->free;
```


