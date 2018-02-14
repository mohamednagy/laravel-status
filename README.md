# Laravel-Status
manage status like moderation or anysomething else for elqouent models

# Install
```
$ composer install nagy/laravel-status
```
# Using!
add the status column to your migration
```
$table->tinyInteger('status')->default(0);
```
define the status into your elquent model
```
protected static $status = [
        0 => 'pending',
        1 => 'approved',
        2 => 'rejected'
];
```

if you need to override the databse *column* or rename it
```
 protected static $status_column = 'status';
```
include the `HasStatus` into your model
```
use Nagy\LaravelStatus\Traits\HasStatus;

class User extends Model
{
    use HasStatus;
```

## exmaples
*laravel-status* works daynamicly according to your defined status, for example for status `pending` then you will be able use it as `isPending`,`setPending`, `onlyPending`

```
// check if model has status
$model->isPending();

// update model to another status
$model->setApproved();

// get only models that has status
Model::onlyPending()->get();

// or use within a query builder
Model::where('age', '>', 18)->onlyPending()->get();
```
