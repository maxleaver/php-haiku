# PHP Haiku Generator
Converts a text string<br />
to haiku if possible<br />
or it does nothing

## Installation
```
composer require maxleaver/php-haiku
```

## Usage
A single static<br />
method will return FALSE if<br />
it can't be haiku.

Otherwise it will<br />
return an array with each<br />
of the lines inside.

```php
<?php

use PhpHaiku\Haiku;

echo Haiku::generate($text);
```

## Contributing
Improve this project<br />
with bug reports, pull requests and<br />
feature suggestions.