<?php

declare(strict_types=1);

namespace Khalyomede\PhpTypo;

enum CheckStatus: int
{
    case Ok = 0;
    case TypoFound = 1;
}
