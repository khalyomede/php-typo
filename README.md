# php-typo/php-typo

A command line tool to validate files do not have English typo in variables, methods, functions, ...

## Summary

- [About](#about)
- [Installation](#installation)
- [Examples](#examples)
- [Tests](#tests)

## About

I searched for a tool to correct english typos across a code base, but only ended up finding VSCode plugins. I know there is PHPStorm plugins as well (and probably similar tools on others code editors), but I need a single, unique way to correct my english typos so that other contributors to my code base will also have the same results, and do not need to install anything on their editor.

English words list based on [dwyl/english-words](https://github.com/dwyl/english-words).

## Features

- Validates your code do not have typos in
  - class names and their properties, methods and their parameters
  - interfaces names
  - enum names and their cases
  - variables names
  - function names and their parameters
  - constants and class constants
- Shipped with a list of known english words for fast quick start
- Dead simple way to ignore/whitelist additional words

## Installation

On your root folder, on the terminal type this command:

```bash
./vendor/bin/php-typo init
```

This will create a file `php-typo.json` at the root of your folder with the following content:

```json
{
  "include": [
    "src"
  ],
  "exclude": [],
  "whitelist": [
    "vendor/khalyomede/php-typo/src/words/english.json"
  ]
}
```

## Examples

- [1. Run your first check](#1-run-your-first-check)
- [2. Add more words/whitelist words](#2-add-more-words-whitelist-words)

### 1. Run your first check

In this example, we will just run the command, which by default will search on the files you specified in your "php-typo.json" config file.

We will assume we have the following file content in "src/index.php":

```php
require __DIR__ . "/../vendor/autoload.php";

$gretingMessage = "Hello, world";

echo $gretingMessage . PHP_EOL;
```

Run the command:

```bash
khalyomede@ubuntu:~/programming/php-typo$ ./vendor/bin/php-typo check

Getting config...
Config found (0.08 ms.). Getting file list...
File list found with 1 files to scan (0.26 ms.). Scanning...

public/index.php:3
  variable "gretingMessage" contains an unknown word "greting".

public/index.php:5
  variable "gretingMessage" contains an unknown word "greting".

Total typos  2
Total files  1
Time spent   125.94 ms.
Memory used  98.18 MB
```

## 2. Add more words/whitelist words

In this example, we will use an additional list of English words to extend the base list of words shipped with this package. This is equivalent to "ignoring" some words.

In the config file at "php-typo.json", add your custom JSON word list:

```json
{
  "include": [
    "src"
  ],
  "exclude": [],
  "whitelist": [
    "vendor/khalyomede/php-typo/src/words/english.json",
    "custom.json"
  ]
}
```

The list of words should be an array of strings. Here is what you could have in your file "custom.json":

```json
[
  "greting",
  "php"
]
```

On the next check, the words "greting" and "php" will be counted as correct words.

## Tests

```bash
composer run test
composer run analyse
composer run lint
composer run updates
composer audit
```

Or

```bash
composer run all
```
