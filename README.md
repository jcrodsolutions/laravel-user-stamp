
# Laravel User Stamp✒️
[![PHP Version](https://img.shields.io/packagist/php-v/jcrodsolutions/laravel-user-stamp.svg)](https://packagist.org/packages/jcrodsolutions/laravel-user-stamp)
[![Packagist Version](https://img.shields.io/packagist/v/jcrodsolutions/laravel-user-stamp.svg)](https://packagist.org/packages/jcrodsolutions/laravel-user-stamp)
[![Packagist](https://img.shields.io/packagist/dt/jcrodsolutions/laravel-user-stamp.svg)](https://packagist.org/packages/jcrodsolutions/laravel-user-stamp)
[![Github](https://img.shields.io/github/license/jcrodsolutions/laravel-user-stamp.svg)](https://packagist.org/packages/jcrodsolutions/laravel-user-stamp)

Enables automatic user stamp on created_by and updated_by fields within a model.

## Usage
Inside any model having the created_by and/or updated_by fields you should use the trait as follows.
```
use Jcrodsolutions\LaravelUserStamp\App\Traits\UserStampTrait;
use Illuminate\Database\Eloquent\Model;

class  MyModel  extends  Model
{
	use  UserStampTrait;
	protected  $fillable = ['codename','name','created_by','updated_by'];
	// ...
}
```
## Global defaults
Globally, the default field names the trait will try to populate are 
* active
* created_by
* updated_by

Whenever you want to change this just publish the vendor config file. 
```
myproject# php artisan vendor:publish
```
Proceed by selecting the provider "Provider: Jcrodsolutions\LaravelUserStamp\UserStampServiceProvider".

## Custom field names in a model
If you need to customize any of the fields in the model you should override the defaults by defining protected variables as the following example
```

use Jcrodsolutions\LaravelUserStamp\App\Traits\UserStampTrait;
use Illuminate\Database\Eloquent\Model;

class  MyModel  extends  Model
{
	use  UserStampTrait;
	protected  $fillable = ['codename','name','created_by','updated_by'];
	protected  static  $active = 'activo';
	protected  static  $createdBy = 'creado_por';
	protected  static  $updatedBy = 'actualizado_por';
	
	//...
}
```
## License
MIT.