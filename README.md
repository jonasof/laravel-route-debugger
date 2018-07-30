# Laravel route debugger

## Installation

`composer global require jonasof/laravel-route-debugger`

## Usage

Execute in your laravel project root directory:

`laravel-route-debugger find GET /`

Will return:

```
File: /home/user/laravel-project/routes/web.php:16
Controller: App\Http\Controllers\HomeController@get
Controller File: /home/user/laravel-project/app/Http/Controllers/HomeControler.php:10
```

The script more complex routes like POST /user/123/comments since it uses
the default laravel route parser.

You can also use `--json` flag to return the result as json in this format:

```
{
  "route": {
    "file": "\/home\/user\/laravel-project\/routes\/api.php",
    "line": 16
  },
  "controller": {
     "action": "App\\Http\\Controllers\\HomeController@get",
     "file": "\/home\/user\/laravel-project\/app\/Http\/Controllers\/HomeController.php",
     "line": 10
    }
  }+
}
```