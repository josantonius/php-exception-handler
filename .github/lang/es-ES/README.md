# PHP ExceptionHandler library

[![Latest Stable Version](https://poser.pugx.org/josantonius/exception-handler/v/stable)](https://packagist.org/packages/josantonius/exception-handler)
[![License](https://poser.pugx.org/josantonius/exception-handler/license)](LICENSE)
[![Total Downloads](https://poser.pugx.org/josantonius/exception-handler/downloads)](https://packagist.org/packages/josantonius/exception-handler)
[![CI](https://github.com/josantonius/php-exception-handler/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/josantonius/php-exception-handler/actions/workflows/ci.yml)
[![CodeCov](https://codecov.io/gh/josantonius/php-exception-handler/branch/main/graph/badge.svg)](https://codecov.io/gh/josantonius/php-exception-handler)
[![PSR1](https://img.shields.io/badge/PSR-1-f57046.svg)](https://www.php-fig.org/psr/psr-1/)
[![PSR4](https://img.shields.io/badge/PSR-4-9b59b6.svg)](https://www.php-fig.org/psr/psr-4/)
[![PSR12](https://img.shields.io/badge/PSR-12-1abc9c.svg)](https://www.php-fig.org/psr/psr-12/)

**Traducciones**: [English](/README.md)

Biblioteca PHP para manejar excepciones.

---

- [Instalación](#instalación)
- [Requisitos](#requisitos)
- [Clases disponibles](#clases-disponibles)
  - [Clase ExceptionHandler](#clase-exceptionhandler)
- [Excepciones utilizadas](#excepciones-utilizadas)
- [Uso](#uso)
- [Tests](#tests)
- [Tareas pendientes](#tareas-pendientes)
- [Registro de Cambios](#registro-de-cambios)
- [Contribuir](#contribuir)
- [Patrocinar](#patrocinar)
- [Licencia](#licencia)

---

## Requisitos

- Sistema operativo: Linux | Windows.

- Versiones de PHP: 8.1 | 8.2.

## Instalación

La mejor forma de instalar esta extensión es a través de [Composer](http://getcomposer.org/download/).

Para instalar **PHP ExceptionHandler library**, simplemente escribe:

```console
composer require Josantonius/exception-handler
```

El comando anterior sólo instalará los archivos necesarios,
si prefieres **descargar todo el código fuente** puedes utilizar:

```console
composer require josantonius/exception-handler --prefer-source
```

También puedes **clonar el repositorio** completo con Git:

```console
git clone https://github.com/josantonius/php-exception-handler.git
```

## Clases disponibles

### Clase ExceptionHandler

`Josantonius\ExceptionHandler\ExceptionHandler`

Establece un manejador de excepciones:

```php
/**
 * Sets a exception handler.
 *
 * @param callable $callback          Exception handler function.
 * @param string[] $runBeforeCallback Method names to call in the exception before run callback.
 * @param string[] $runAfterCallback  Method names to call in the exception after run callback.
 * 
 * @throws NotCallableException     if the callback is not callable.
 * @throws WrongMethodNameException if the method names are not string or are empty.
 * 
 * @see https://www.php.net/manual/en/functions.first_class_callable_syntax.php
 */
public function __construct(
    private callable $callback,
    private array $runBeforeCallback = [],
    private array $runAfterCallback = []
);
```

## Excepciones utilizadas

```php
use Josantonius\ExceptionHandler\Exceptions\NotCallableException;
use Josantonius\ExceptionHandler\Exceptions\WrongMethodNameException;
```

## Uso

Ejemplo de uso para esta biblioteca:

### Establece un manejador de excepciones básico

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

function handler(\Throwable $exception) { /* hacer algo */ }

new ExceptionHandler(
    callback: handler(...)
);

/**
 * Si se lanza una excepción, se ejecuta lo siguiente:
 *
 * handler($exception)
 */
```

### Establece los métodos a llamar antes de ejecutar el *callback*

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function context(): void { /* hacer algo */ }
}

class Handler {
    public function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* hacer algo */
        }
    }
}

new ExceptionHandler(
    callback: (new Handler())->exceptions(...),
    runBeforeCallback: ['context']
);

/**
 * Si se lanza FooException(), se ejecuta lo siguiente:
 * 
 * FooException->context()
 * Handler->exceptions($exception)
 */
```

### Establece los métodos a ejecutar después de llamar al *callback*

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function report(): void { /* hacer algo */ }

    public function render(): void { /* hacer algo */ }
}

class Handler {
    public static function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* hacer algo */
        }
    }
}

new ExceptionHandler(
    callback: Handler::exceptions(...),
    runAfterCallback: ['report', 'render']
);

/**
 * Si se lanza FooException(), se ejecuta lo siguiente:
 * 
 * Handler::exceptions($exception)
 * FooException->report()
 * FooException->render()
 */
```

### Sets methods to execute before and after calling the callback

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function context(): void { /* do something */ }

    public function report(): void { /* do something */ }

    public function render(): void { /* do something */ }
}

function exceptionHandler(Throwable $exception) { /* do something */ }

new ExceptionHandler(
    callback: exceptionHandler(...),
    runBeforeCallback: ['context', 'logger'],
    runAfterCallback: ['report', 'render']
);

/**
 * If FooException() is thrown, the following is executed:
 * 
 * FooException->context()
 * exceptionHandler($exception)
 * FooException->report()
 * FooException->render()
 * 
 * Se ignora FooException->logger(), no existe en la excepción.
 */
```

## Tests

Para ejecutar las [pruebas](tests) necesitarás [Composer](http://getcomposer.org/download/)
y seguir los siguientes pasos:

```console
git clone https://github.com/josantonius/php-exception-handler.git
```

```console
cd php-exception-handler
```

```console
composer install
```

Ejecutar pruebas unitarias con [PHPUnit](https://phpunit.de/):

```console
composer phpunit
```

Ejecutar pruebas de estándares de código con [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

```console
composer phpcs
```

Ejecutar pruebas con [PHP Mess Detector](https://phpmd.org/) para
detectar inconsistencias en el estilo de codificación:

```console
composer phpmd
```

Ejecutar todas las pruebas anteriores:

```console
composer tests
```

## Tareas pendientes

- [ ] Añadir nueva funcionalidad
- [ ] Mejorar pruebas
- [ ] Mejorar documentación
- [ ] Mejorar la traducción al inglés en el archivo README
- [ ] Refactorizar código para las reglas de estilo de código deshabilitadas
(ver [phpmd.xml](phpmd.xml) y [phpcs.xml](phpcs.xml))

## Registro de Cambios

Los cambios detallados de cada versión se documentan en las
[notas de la misma](https://github.com/josantonius/php-exception-handler/releases).

## Contribuir

Por favor, asegúrate de leer la [Guía de contribución](CONTRIBUTING.md) antes de hacer un
*pull request*, comenzar una discusión o reportar un *issue*.

¡Gracias por [colaborar](https://github.com/josantonius/php-exception-handler/graphs/contributors)! :heart:

## Patrocinar

Si este proyecto te ayuda a reducir el tiempo de desarrollo,
[puedes patrocinarme](https://github.com/josantonius/lang/es-ES/README.md#patrocinar)
para apoyar mi trabajo :blush:

## Licencia

Este repositorio tiene una licencia [MIT License](LICENSE).

Copyright © 2022-actualidad, [Josantonius](https://github.com/josantonius/lang/es-ES/README.md#contacto)
