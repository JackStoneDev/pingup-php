#pingup-php

pingup-php is an unofficial PHP wrapper for the [Pingup Booking API](http://developers.pingup.com).

###Installation and getting started

pingup-php is designed to be as easy as possible to use.  First, include the `pingup.class.php` file, like so:

```php
require_once("pingup.class.php");
```

Now you must obtain an access token.  See the documentation for instructions.  Once you have a valid token, you can then create a new `pingup` object for future API calls.  Leave the second constructor argument blank to make all further calls in the live API environment, or pass in true to use the sandboxed environment (where no actual appointments can be booked).

```php
// Create a pingup object that makes requests in the live environment
$pingup = new pingup("YOUR-ACCESS-TOKEN");
// Create a pingup object that makes requests in the sandboxed environment
$pingup = new pingup("YOUR-ACCESS-TOKEN", true);
```

And there you have it! You may now call any method from the wrapper that you would like.  For more comprehensive explanations, see the documentation.

## License

pingup-php is licensed under a MIT license.

Copyright (c) 2014 Jack Stone.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

