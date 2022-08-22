<?php

declare(strict_types=1);

afterEach(fn (): bool => unlink(__DIR__ . "/../php-typo.json"));

test("binary is callable", function (): void {
    file_put_contents(__DIR__ . "/../php-typo.json", json_encode([
        "include" => [
            "tests/no-errors",
        ],
        "exclude" => [],
        "whitelist" => [
            "src/words/english.json",
        ],
    ]));

    $output = "";
    $exitCode = 1;

    exec("composer run typo", $output, $exitCode);

    expect($exitCode)->toBe(0);
});
