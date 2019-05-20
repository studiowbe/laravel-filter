# laravel-filter
Provides a unified filter interface for Collections, Database and Eloquent Queries
   
## Installation
The (highly) recommended way to install this package is by using [Composer](https://getcomposer.org/)
   
```bash
composer require studiow/laravel-filter
```
  
## Creating 
Use the Filter::make method to create a filter interface:
```php
//From a collection
\Studiow\Laravel\Filtering\Filter::make(collect([]));

//From an array (or any datastructure supported by Illuminate\Support\Collection)
\Studiow\Laravel\Filtering\Filter::make([]));

//From an eloquent model query
\Studiow\Laravel\Filtering\Filter::make(ModelName::query());

//From an eloquent model instance
\Studiow\Laravel\Filtering\Filter::make($myModelInstance));

//From a Query Builder
\Studiow\Laravel\Filtering\Filter::make(\DB::table('the_table_name'));

```
## Usage
### Simple filtering
```php
$collection = collect([
        ['product' => 'Desk', 'price' => 200],
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Bookcase', 'price' => 150],
        ['product' => 'Door', 'price' => 100],
]);

$filter = \Studiow\Laravel\Filtering\Filter::make($collection);

//add a filter
$filtered = $filter->where('price', 100)->items();

$filtered->all();

/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Door', 'price' => 100],
    ]
*/

//add a filter with an operator
$cheap = $filter->where('price',  '<', 150)->items();
$cheap->all();

/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Door', 'price' => 100],
    ]
*/
``` 

### Combining filters
use the andWhere and orWhere methods to combine various filters
```php
$cheapChairs = $filter
    ->where('product', '=', 'Chair')
    ->where('price', '<', 150)
    ->items();
$cheapChairs->all();

/*
    [
        ['product' => 'Chair', 'price' => 100],
    ]
*/

$cheapOrBookcase = $filter
    ->where('price', '<', 150)
    ->orWhere('product', 'Bookcase')->items();
$cheapOrBookcase->all();

/*
    [
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Door', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
    ]
*/ 
```

## Operators
The following operators are supported

- =, ==, ===
- !=, !=
- <>, <, >, <=, >=
- BETWEEN
- IN, NOT IN
- IS NULL, IS NOT NULL
- LIKE, NOT LIKE

### A note on (NOT) LIKE
Use % as a wildcard:
```php
$productsEndingInR = $filter
    ->where('product', 'LIKE', '%r')
    ->items();
$productsEndingInR->all();

/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Door', 'price' => 100],
    ]
*/

$cheapOrBookcase = $filter
    ->where('price', '<', 15O)
    ->orWhere('product', 'Bookcase')->items();
$cheapOrBookcase->all();

/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Bookcase', 'price' => 150],
    ]
*/ 
```

## Results
Results are returned as a collection from the items() method. 