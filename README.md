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

Json Response
```json
{
"firstLink": null,
"backLink": null,
"nextLink": "?page=2",
"lastLink": "?page=6",
"currentPage": 1,
"totalPageCount": 6,
"recordCount": 29,
"pageURL": "/webapp/p/DB.php",
"data": [
{
"id": "1",
"company": "Company A",
"last_name": "Bedecs",
"first_name": "Anna",
"email_address": null,
"job_title": "Owner",
"business_phone": "(123)555-0100",
"home_phone": null,
"mobile_phone": null,
"fax_number": "(123)555-0101",
"address": "123 1st Street",
"city": "Seattle",
"state_province": "WA",
"zip_postal_code": "99999",
"country_region": "USA",
"web_page": null,
"notes": null,
"attachments": ""
},
{
"id": "2",
"company": "Company B",
"last_name": "Gratacos Solsona",
"first_name": "Antonio",
"email_address": null,
"job_title": "Owner",
"business_phone": "(123)555-0100",
"home_phone": null,
"mobile_phone": null,
"fax_number": "(123)555-0101",
"address": "123 2nd Street",
"city": "Boston",
"state_province": "MA",
"zip_postal_code": "99999",
"country_region": "USA",
"web_page": null,
"notes": null,
"attachments": ""
},
{
"id": "3",
"company": "Company C",
"last_name": "Axen",
"first_name": "Thomas",
"email_address": null,
"job_title": "Purchasing Representative",
"business_phone": "(123)555-0100",
"home_phone": null,
"mobile_phone": null,
"fax_number": "(123)555-0101",
"address": "123 3rd Street",
"city": "Los Angelas",
"state_province": "CA",
"zip_postal_code": "99999",
"country_region": "USA",
"web_page": null,
"notes": null,
"attachments": ""
},
{
"id": "4",
"company": "Company D",
"last_name": "Lee",
"first_name": "Christina",
"email_address": null,
"job_title": "Purchasing Manager",
"business_phone": "(123)555-0100",
"home_phone": null,
"mobile_phone": null,
"fax_number": "(123)555-0101",
"address": "123 4th Street",
"city": "New York",
"state_province": "NY",
"zip_postal_code": "99999",
"country_region": "USA",
"web_page": null,
"notes": null,
"attachments": ""
},
{
"id": "5",
"company": "Company E",
"last_name": "O’Donnell",
"first_name": "Martin",
"email_address": null,
"job_title": "Owner",
"business_phone": "(123)555-0100",
"home_phone": null,
"mobile_phone": null,
"fax_number": "(123)555-0101",
"address": "123 5th Street",
"city": "Minneapolis",
"state_province": "MN",
"zip_postal_code": "99999",
"country_region": "USA",
"web_page": null,
"notes": null,
"attachments": ""
}
]
}
```
