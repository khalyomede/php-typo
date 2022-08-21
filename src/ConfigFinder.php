<?php

declare(strict_types=1);

namespace PhpTypo\PhpTypo;

use PhpTypo\PhpTypo\Exceptions\ConfigFileInvalidJsonFormatException;
use PhpTypo\PhpTypo\Exceptions\ConfigFileNotFoundException;
use PhpTypo\PhpTypo\Exceptions\FileNotReadableException;

final class ConfigFinder
{
    public static function get(): Config
    {
        $filePath = "php-typo.json";

        if (!is_file($filePath)) {
            throw new ConfigFileNotFoundException("Config file not found at this location: $filePath");
        }

        $content = file_get_contents($filePath);

        if (!is_string($content)) {
            throw new FileNotReadableException("The config file is not readable at this location: $filePath");
        }

        $data = json_decode($content, associative: true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = $data === false
                ? "Unknown error"
                : json_last_error_msg();

            throw new ConfigFileInvalidJsonFormatException("The config file is not a valid JSON ($error) at this location: $filePath");
        }

        if (!is_array($data)) {
            throw new ConfigFileInvalidJsonFormatException("The config file must be an object at this location: $filePath");
        }

        $include = $data["include"] ?? [];
        $ignore = $data["ignore"] ?? [];
        $whitelist = $data["whitelist"] ?? [];

        if (!is_array($include)) {
            throw new ConfigFileInvalidJsonFormatException("Config file key \"include\" must be an array");
        }

        if (!is_array($ignore)) {
            throw new ConfigFileInvalidJsonFormatException("Config file key \"ignore\" must be an array");
        }

        if (!is_array($whitelist)) {
            throw new ConfigFileInvalidJsonFormatException("Config file key \"whitelist\" must be an array");
        }

        return new Config(
            include: $data["include"] ?? [],
            ignore: $data["ignore"] ?? [],
            whitelist: $data["whitelist"] ?? []
        );
    }
}
