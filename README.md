# PHP Haiku Generator
Converts a text string<br />
to haiku if possible<br />
or it returns false.

## Installation
```
composer require maxleaver/php-haiku
```

## Basic Usage
```php
<?php

use PhpHaiku\Haiku;

$haiku = new Haiku();
$haiku->setText('Converts a text string to haiku if possible or it returns false.');

if ($haiku->isHaiku()) {
	echo $haiku->getFirstLine(); // Converts a text string
	echo $haiku->getSecondLine(); // to haiku if possible
	echo $haiku->getThirdLine(); // or it returns false.
}

```

## Contributing
Improve this project<br />
with bug reports, pull requests and<br />
feature suggestions.