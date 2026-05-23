planet-ubuntuusers-json
=======================

Tiny PHP app that turns the `planet.ubuntuusers.de` RSS feed into:

- a simple HTML page (`/index.php`)
- JSON (`/json.php`)
- JSONP (`/json.php?callback=app.feed`)

## Requirements

- PHP 8.2+
- Composer 2

## Install

```bash
composer install
```

## Local checks

```bash
composer validate
composer lint
```
