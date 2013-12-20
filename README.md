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
    array(
    )
);
if ($smcFunc['db_num_rows']($request) == 0)
    return array();
while ($row = $smcFunc['db_fetch_assoc']($request))
{
    if ($row['id_cat'] != $category_id)
        $cats[] = $row['id_cat'];
    if ($row['id_cat'] == $catOptions['move_after'])
        $cats[] = $category_id;
    $cat_order[$row['id_cat']] = $row['cat_order'];
}
$smcFunc['db_free_result']($request);
```

**new style**
```php
use Inter\DB;

$cat_order = DB::get('
    SELECT id_cat, cat_order
    FROM {db_prefix}categories',
    [],
    function (&$cat_order, $row) use ($category_id, $catOptions)
    {
        if ($row['id_cat'] != $category_id)
            $cats[] = $row['id_cat'];
        if ($row['id_cat'] == $catOptions['move_after'])
            $cats[] = $category_id;
        $cat_order[$row['id_cat']] = $row['cat_order'];
    }
);
```
