# Simple PDO Pagination Package

This is a simple PHP package that lets you paginate your select queries 
making it easier to navigate a list of records with simple navigation links.
### Composer Installation

```composer require allan/pagination```

### Usage
```
<?php

use Pagination\Pager;

$p = new \PDO("mysql:host=localhost;port=3306;dbname=labstore", 'root', '');
$p = new Pager(self::$pdoConnection, "SELECT * FROM inventory");
$p->setPageUrl("http://localhost/inventory");
$p->setPerPage(10);
$p->paginate();
```

for JSON
```
$p->paginateJSON();
```
