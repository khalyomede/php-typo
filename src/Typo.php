<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

final class Typo
{
    public function __construct(
        public readonly string $name,
        public readonly TypoType $type,
        public readonly string $file,
        public readonly int $line,
        public readonly string $error
    ) {
    }
}
