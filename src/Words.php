<?php

declare(strict_types=1);

namespace PhpTypo\PhpTypo;

use PhpTypo\PhpTypo\Exceptions\ConfigFileInvalidJsonFormatException;
use PhpTypo\PhpTypo\Exceptions\FileNotReadableException;
use PhpTypo\PhpTypo\Exceptions\FileOrFolderNotFoundException;
use PhpTypo\PhpTypo\Exceptions\NotAFileException;

final class Words
{
    public const MAX_SUGGESTION_COUNT = 2;

    /**
     * @var array<string>
     */
    private static array $words = [];

    public static function exist(string $word): bool
    {
        return in_array($word, self::$words, true);
    }

    /**
     * @param array<string> $files
     */
    public static function setFromJsonFiles(array $files): void
    {
        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new FileOrFolderNotFoundException("The following whitelist file does not exist: $file");
            }

            if (!is_file($file)) {
                throw new NotAFileException("The following whitelist path is not a file: $file");
            }

            if (!is_readable($file)) {
                throw new FileNotReadableException("The following whitelist file is not readable: $file");
            }

            $content = file_get_contents($file);

            if ($content === false) {
                throw new FileNotReadableException("Could not open the following whitelist file: $file");
            }

            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $error = json_last_error_msg();

                throw new ConfigFileInvalidJsonFormatException("Whitelist file is not a valid JSON ($error) at path: $file");
            }

            if (!is_array($data)) {
                throw new ConfigFileInvalidJsonFormatException("Whitelist file must be an array at path: $file");
            }

            foreach ($data as $word) {
                if (!is_string($word)) {
                    throw new ConfigFileInvalidJsonFormatException("Whitelist file must be an array of strings at path: $file");
                }

                self::$words[] = $word;
            }
        }

        self::$words = array_unique(self::$words);
    }

    /**
     * @param array<string> $words
     */
    public static function ignore(array $words): void
    {
        self::$words = array_diff(self::$words, $words);
    }
}
