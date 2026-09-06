<?php

namespace JeffersonGoncalves\DataForSeo\Exceptions;

use RuntimeException;

/**
 * Raised when the DataForSEO API answers a request with a non-2xx HTTP
 * status. Carries the response's error message (`status_message`/`message`,
 * falling back to the raw body) and the HTTP status code.
 */
class DataForSeoException extends RuntimeException
{
    public function __construct(string $message, public readonly int $statusCode)
    {
        parent::__construct($message, $statusCode);
    }
}
