# 📎 Uri

Uri is a helper class that receives a `string` URI and parses it automatically exposing simple helpful functionalities.

## Installation

⏰ Installation guide coming soon...

## Quick Example

Let's say we have a long URI and we would just like to extract a single query parameter, in this case `target_parameter`, from it. Instead of going through parsing the URI step by step and checking if the query parameter exists and then fetching it, I can simply do the following:

```php
function getQueryParamFromLongUri()
{
    $uri = "https://www.example-blog.com/articles/the-slug-of-the-article?some_parameter=12&target_parameter=value#the-fragment";
    
    Uri::parse($uri)->query("target_parameter"); // "value"
}
```

## Usage

To add the Uri class model you must:

- Include the file `use Path\To\Uri;`
- Parse a string URI by calling the `parse(string $uri)` method.

## Example

```php
namespace MyNamespace;

use Path\To\Uri;

class MyClass
{
    public function myFunction()
    {
        $raw = "http://www.google.com/path/to/file?my=test&active#fragment";

        $uri = Uri::parse($raw);
    }
}
```

After parsing a `string` URI, you will have access to the methods below.

```php
$uri->scheme();         // http
$uri->host();           // www.google.com
$uri->port();           // null
$uri->path();           // /path/to/file
$uri->queries();        // [ "my" => "test", "active" => null ]
$uri->queryHas("my");   // true    
$uri->query("my");      // test
$uri->fragment();       // fragment
```

## Adding a Global Helper Function

In some cases, it would be a good idea to add a helper function that parses a URI. This will make your code lighter.

```php
if (!function_exists("uri")) {
    function uri(string $uri)
    {
        return Uri::parse($uri);
    }
}
```

After creating the helper function, you can directly use it without having to import the `Uri` class and creating a new instance by calling the `parse()` method.

```php
uri("https://www.google.com")->queryHas("query_param"); // false
```

You can name the helper function whatever you want. However, in some frameworks, such as Laravel for example, a helper function `url()` exists so naming this one `url` might cause some problems.

## Authors

- [Joe Karam](https://github.com/joekaram)