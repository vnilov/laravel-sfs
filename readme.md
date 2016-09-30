[![Latest Stable Version](https://poser.pugx.org/vnilov/laravel-sfs/v/stable)](https://packagist.org/packages/vnilov/laravel-sfs)
[![Total Downloads](https://poser.pugx.org/vnilov/laravel-sfs/downloads)](https://packagist.org/packages/vnilov/laravel-sfs)
[![Latest Unstable Version](https://poser.pugx.org/vnilov/laravel-sfs/v/unstable)](https://packagist.org/packages/vnilov/laravel-sfs)
[![License](https://poser.pugx.org/vnilov/laravel-sfs/license)](https://packagist.org/packages/vnilov/laravel-sfs)


### Introduction

This is a simple package to develop file storage which is based on uploaded files on your project. 

### Requirements

- PHP 5.5.9+
- Laravel Framework (currently 5.2)

### Install
1. Run `composer require laravel-sfs`
2. Add this line to your `app.php` config file within **providers** section:
`SimpleFileStorage\SFSServiceProvider::class`
3. Publish config and migration: 
   
   3.1 Publish config: `php artisan vendor:publish --provider="SimpleFileStorage\SFSServiceProvider"
--tag="config"`
   
   3.2 Publish migration
   `php artisan vendor:publish --provider="SimpleFileStorage\SFSServiceProvider"
   --tag="migrations"`
   
   3.3 Run migration by: `php artisan migrate`
   
### Examples

##### Within Controller

```php
   <?
   use App\Http\Controllers\Controller;
   
   class TestController extends Controller 
   {
       private $sfs; 
       
       public function __construct(SimpleFileStorage\SFSFacade $facade) {
            $this->sfs = $facade;    
       }
       
        public function store(Request $request)
        {
           $test_field = $request->input('test_field');
           
            if ($request->hasFile('picture')) {
                $file_id = $this->sfs->save($request->file('picture'));
            }
            
            TestModel::create(['name' => $test_field, 'picture' => $file_id]);
        }
        
        public function get($id) {
            $res = TestModel::findOrFail($id);
            $picture = $this->sfs->getUrl($res->picture);
            return response()->json(['name' => $res->name, 'picture'=>$picture]);
        }
   }


