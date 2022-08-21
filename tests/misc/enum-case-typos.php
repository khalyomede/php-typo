<?php

declare(strict_types=1);

enum Status: int
{
    case Ok = 200;
    case ServerErorr = 500;
}
