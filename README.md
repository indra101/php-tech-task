PHP Technical Test
========================

This is the service application for technical test on Loadsmile by Indra Setiawan

Installation
------------

There are several plugins that must be installed, that is annotations, symfony/flex, symfony/maker-bundle, and phpunit for the unit test. Plugin installation can be done using composer.

```bash
$ composer require annotations
$ composer require symfony/flex
$ composer require symfony/maker-bundle
$ composer require --dev phpunit
```

Usage
-----

You can access the application in your
browser at the given URL (<https://localhost:8000> by default) and add: 
1. '\lunch' to get the suggested recipes for today
2. '\lunch\YYYY-mm-dd' to get the suggested recipes for the specified date


Tests
-----

Execute this command to run tests:

```bash
$ php bin/phpunit
```
The tests will be used to check the constructor class for Ingredient class and Recipe class, wether it can instantiate the class with the proper JSON input or an invalid one.
