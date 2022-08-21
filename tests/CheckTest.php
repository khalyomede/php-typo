<?php

declare(strict_types=1);

use Khalyomede\PhpTypo\Commands\Check;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

afterEach(fn (): bool => unlink(__DIR__ . "/../php-typo.json"));

test('it can run check which checks for typos', function (): void {
    file_put_contents(__DIR__ . "/../php-typo.json", json_encode([
        "include" => [
            "tests/misc",
        ],
        "exclude" => [],
        "whitelist" => [
            "src/words/english.json",
        ],
    ]));

    $application = new Application();

    $application->add(new Check());

    $command = $application->find("check");
    $commandTester = new CommandTester($command);

    $code = $commandTester->execute([]);

    $output = $commandTester->getDisplay();

    expect($output)->toContain(join("\n", [
        "TTTTTTTTTTT",
        "",
        "tests/misc/class-constant-typos.php:7",
        '  class constant "APLICATION_PLAIN" contains an unknown word "aplication".',
        "",
        "tests/misc/class-typos.php:5",
        '  class "Locla" contains an unknown word "locla".',
        "",
        "tests/misc/constant-typos.php:5",
        '  constant "DRIVER_SQL" contains an unknown word "sql".',
        "",
        "tests/misc/enum-case-typos.php:8",
        '  enum case "ServerErorr" contains an unknown word "erorr".',
        "",
        "tests/misc/enum-typos.php:5",
        '  enum "Reslt" contains an unknown word "reslt".',
        "",
        "tests/misc/function-parameter-typos.php:5",
        '  variable "tpye" contains an unknown word "tpye".',
        "",
        "tests/misc/function-typos.php:5",
        '  function "getNotfication" contains an unknown word "notfication".',
        "",
        "tests/misc/interface-typos.php:5",
        '  interface "CanSendEmial" contains an unknown word "emial".',
        "",
        "tests/misc/method-typos.php:7",
        '  method "getFisrtNameAttribute" contains an unknown word "fisrt".',
        "",
        "tests/misc/property-typo.php:7",
        '  property "naem" contains an unknown word "naem".',
        "",
        "tests/misc/variable-typos.php:5",
        '  variable "gretingMessage" contains an unknown word "greting".',
        "",
        "tests/misc/variable-typos.php:7",
        '  variable "gretingMessage" contains an unknown word "greting".',
        "",
        "Total typos  12",
        "Total files  11",
    ]));
    expect($code)->toBe(0);
});
