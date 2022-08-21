<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

use Khalyomede\PhpTypo\Exceptions\ConfigWriteException;

final class Config
{
    public const PATH = "php-typo.json";

    public function __construct(
        /**
         * @var array<string>
         */
        public readonly array $include,

        /**
         * @var array<string>
         */
        public readonly array $ignore,

        /**
         * @var array<string>
         */
        public readonly array $whitelist
    ) {
    }

    public static function exists(): bool
    {
        return file_exists(self::PATH) && is_file(self::PATH);
    }

    public static function write(): void
    {
        $data = json_encode([
            "include" => [
                "src"
            ],
            "exclude" => [],
            "whitelist" => [
                /**
                 * @todo Can we use a Composer global variable to get the root folder installed instead?
                 */
                "vendor/khalyomede/php-typo/src/words/english.json"
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (!is_string($data)) {
            throw new ConfigWriteException("Could not encode JSON before writing config file at " . self::PATH);
        }

        $success = file_put_contents(self::PATH, $data);

        if ($success === false) {
            throw new ConfigWriteException("Could not write config file at " . self::PATH);
        }
    }
}
