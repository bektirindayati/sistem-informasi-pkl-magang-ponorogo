<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Kegagalan chatbot yang pesannya aman ditampilkan ke pengunjung.
 * Detail teknis dicatat di log oleh yang melempar, bukan di pesan ini.
 */
class ChatbotException extends RuntimeException
{
    public function __construct(
        string $message,
        private int $status = 503,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function status(): int
    {
        return $this->status;
    }
}