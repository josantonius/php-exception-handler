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
- [Cómo empezar y ejemplos](#cómo-empezar-y-ejemplos)
- [Métodos disponibles](#métodos-disponibles)
- [Uso](#uso)
- [Tests](#tests)
- [Tareas pendientes](#tareas-pendientes)
- [Registro de Cambios](#registro-de-cambios)
- [Contribuir](#contribuir)
- [Patrocinar](#patrocinar)
- [Licencia](#licencia)

---

## Requisitos

Esta biblioteca es compatible con las versiones de PHP: 8.1.

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

## Métodos disponibles

Métodos disponibles en esta biblioteca:

### Establece un manejador de excepciones

```php
new ExceptionHandler(
    callable $callback,
    string[] $runBeforeCallback = [],
    string[] $runAfterCallback = []
);
```

**@param** callable `$callback` Función para manejo de excepciones.

**@param** array `$runBeforeCallback` Nombres de métodos a llamar en la excepción antes de
ejecutar el *callback*.

**@param** array `$runAfterCallback` Nombres de métodos a llamar en la excepción después de
ejecutar el *callback*.

**@throws** `NotCallableException` si la llamada de retorno no es de tipo *callable*.

**@throws** `WrongMethodNameException` si el nombre del método no es *string*.

@see <https://www.php.net/manual/en/functions.first_class_callable_syntax.php> para más información
sobre la sintaxis de las llamadas de primera clase.

## Cómo empezar

Para utilizar esta biblioteca:

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

new ExceptionHandler(/*...*/);
```

## Uso

Ejemplo de uso para esta biblioteca:

### Establece un manejador de excepciones básico

```php
function handler(Throwable $exception) { /* hacer algo */ }
```

```php
new ExceptionHandler(
    callback: handler(...)
);
```

Si se lanza una excepción:

- `handler($exception)` *callback* será ejecutado

### Establece los métodos a llamar antes de ejecutar el *callback*

```php
class FooException extends \Exception
{
    public function context(): void { /* hacer algo */ }
}
```

```php
class Handler {
    public function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* hacer algo */
        }
    }
}
```

```php
new ExceptionHandler(
    callback: (new Handler())->exceptions(...),
    runBeforeCallback: ['context']
);
```

Si se lanza `FooException()`:

- `FooException->context()` será llamado
- `Handler->exceptions($exception)` *callback* será ejecutado

### Sets methods to execute after calling the callback

```php
class FooException extends \Exception
{
    public function report(): void { /* hacer algo */ }

    public function render(): void { /* hacer algo */ }
}
```

```php
class Handler {
    public static function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* hacer algo */
        }
    }
}
```

```php
new ExceptionHandler(
    callback: Handler::exceptions(...),
    runAfterCallback: ['report', 'render']
);
```

Si se lanza `FooException()`:

- `Handler::exceptions($exception)` *callback* será ejecutado
- `FooException->report()` será llamado
- `FooException->render()` será llamado

### Sets methods to execute before and after calling the callback

```php
class FooException extends \Exception
{
    public function context(): void { /* hacer algo */ }

    public function report(): void { /* hacer algo */ }

    public function render(): void { /* hacer algo */ }
}
```

```php
function exceptionHandler(Throwable $exception) { /* hacer algo */ }
```

```php
new ExceptionHandler(
    callback: exceptionHandler(...),
    runBeforeCallback: ['context', 'logger'],
    runAfterCallback: ['report', 'render']
);
```

Si se lanza `FooException()`:

- `FooException->context()` será llamado
- `FooException->logger()` será ignorado, no existe en la excepción
- `exceptionHandler($exception)` *callback* será ejecutado
- `FooException->report()` será llamado
- `FooException->render()` será llamado

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
