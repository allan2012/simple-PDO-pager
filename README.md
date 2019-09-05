# Simple PDO Pagination Package

This is a simple PHP package that lets you paginate your select queries 
making it easier to navigate a list of records with simple navigation links.
### Composer Installation

```composer require allan/pagination```

### Usage
```php
<?php

use Pagination\Pager;

// Create your PDO connection object
$pdo = new \PDO("mysql:host=localhost;port=3306;dbname=testdb", 'root', 'r00t');

// Initiate your pager
$p = new Pager($pdo, "SELECT * FROM users"); 

// Set you page URL
$p->setPageUrl("http://localhost/users");

// Set your per page limit
$p->setPerPage(10);

$dataRecords = $p->paginate()->data;

foreach($dataRecords as $data) {
    echo $data->id.' '.$data->first_name.' '.$data->last_name.'<br />';
}

if(isset($p->paginate()->firstLink)) {
    echo "<a href='{$p->paginate()->firstLink}'> << </a> | ";
}

if(isset($p->paginate()->backLink)) {
    echo "<a href='{$p->paginate()->backLink}'> < </a>";
}

echo "[{$p->paginate()->currentPage}]";

if(isset($p->paginate()->nextLink)) {
    echo "<a href='{$p->paginate()->nextLink}'> > </a> | ";
}

if(isset($p->paginate()->lastLink)) {
    echo "<a href='{$p->paginate()->lastLink}'> >> </a>";
}
```

for JSON
```php
header('Content-Type: application/json');
echo $p->paginateJSON();
```
