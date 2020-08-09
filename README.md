Hosts file parser
=======================
[![Maintainability](https://api.codeclimate.com/v1/badges/05c4eb3a3e4c9fdb9b83/maintainability)](https://codeclimate.com/github/N0rthernL1ghts/hosts-parser/maintainability)


Lightweight and simple library for parsing [hosts file](https://en.wikipedia.org/wiki/Hosts_(file)). 
All operating systems that use this format are supported. 

## Install

Via Composer

``` bash
$ composer require northern-lights/hostsfile-parser
```
It really is that easy!

## Usage
``` php
<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser\Example;

use NorthernLights\HostsFileParser\Host;
use NorthernLights\HostsFileParser\HostsFile;
use NorthernLights\HostsFileParser\Parser;

require __DIR__ . '/vendor/autoload.php';

$parser = new Parser(new HostsFile('/etc/hosts'));

/** @var Host $host */
foreach ($parser->parse() as $host) {
    $domains = $host->getDomains();
    echo 'Host: ' . $host->getIp() . PHP_EOL;
    echo sprintf('Domains: %d -> { %s }', count($domains), implode(', ', $domains)) . PHP_EOL;
    echo 'Line: ' . $host->getLine() . PHP_EOL;
    echo sprintf('Raw entry: [ %s ]', $host) . PHP_EOL;
    echo '--------------------------------------' . PHP_EOL;
}

```

Please note that NorthernLights\HostsFileParser\Parser::parse() is a generator. 

To parse all at once and return array, you can use Parser::parseAll() although discouraged as it could cause OOM in certain cases.

## PSR-2 Standard
Library strives to comply with PSR-2 coding standards, therefore we included following commands:
``` bash
$ composer check-style
$ composer fix-style
```
Note: Second command will actually modify files

## PSR-4 Standard
Library complies with PSR-4 autoloading standard

## Testing

``` bash
$ composer php-lint
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


