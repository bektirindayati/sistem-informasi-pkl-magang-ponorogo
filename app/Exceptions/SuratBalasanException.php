<?php

namespace App\Exceptions;

use RuntimeException;

/** Kegagalan membuat surat/PDF; pesannya aman ditampilkan ke admin. */
class SuratBalasanException extends RuntimeException
{
}