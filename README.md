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
$pdo = new \PDO("mysql:host=localhost;port=3306;dbname=labstore", 'root', '');

// Initiate your pager
$p = new Pager($pdo, "SELECT * FROM inventory"); 

// Set you page URL
$p->setPageUrl("http://localhost/inventory");

// Set your per page limit
$p->setPerPage(10);

$p->paginate();
```

Screenshot 2019-09-05 at 17.25.40

for JSON
```php
header('Content-Type: application/json');
echo $p->paginateJSON();
```
