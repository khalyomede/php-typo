<?php

declare(strict_types=1);

use PhpTypo\PhpTypo\Commands\Init;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

afterEach(fn (): bool => unlink(__DIR__ . "/../php-typo.json"));

test('it can run init which creates a new php-typo.json', function (): void {
    $application = new Application();

    $application->add(new Init());

    $command = $application->find("init");
    $commandTester = new CommandTester($command);

    $code = $commandTester->execute([]);

    $output = $commandTester->getDisplay();

    expect($output)->toBe("Config file written at php-typo.json.\n");
    expect($code)->toBe(0);
});

test("it will return an error if the config file already exists.", function (): void {
    $application = new Application();

    $application->add(new Init());

    $command = $application->find("init");
    $commandTester = new CommandTester($command);

    $code = $commandTester->execute([]);

    $output = $commandTester->getDisplay();

    expect($output)->toBe("Config file written at php-typo.json.\n");
    expect($code)->toBe(0);

    $code = $commandTester->execute([]);

    $output = $commandTester->getDisplay();

    expect($output)->toBe("php-typo.json already exists.\n");
    expect($code)->toBe(1);
});
