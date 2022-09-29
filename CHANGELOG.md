# CHANGELOG

## [v1.0.3](https://github.com/josantonius/php-exception-handler/releases/tag/v1.0.3) (2022-09-29)

* The notation type in the test function names has been changed from camel to snake case for readability.

* Functions were added to document the methods and avoid confusion.

* Disabled the ´CamelCaseMethodName´ rule in ´phpmd.xml´ to avoid warnings about function names in tests.

* The alignment of the asterisks in the comments has been fixed.

* Tests for Windows have been added.

* Tests for PHP 8.2 have been added.

## [v1.0.2](https://github.com/josantonius/php-exception-handler/releases/tag/v1.0.2) (2022-08-11)

* Fixed error when validating method names. Now it will throw exception if it is an empty string.

* Documentation was improved.

## [v1.0.1](https://github.com/josantonius/php-exception-handler/releases/tag/v1.0.1) (2022-08-08)

* Removed an unnecessary callback check in `Josantonius\ExceptionHandler\ExceptionHandler\__construct()`.

## [v1.0.0](https://github.com/josantonius/php-exception-handler/releases/tag/v1.0.0) (2022-08-04)

* First upload.
