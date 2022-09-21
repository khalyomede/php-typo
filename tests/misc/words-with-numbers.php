<?php

declare(strict_types=1);

function abort404(): void
{
    http_response_code(404);

    echo "404 - not found";
}
