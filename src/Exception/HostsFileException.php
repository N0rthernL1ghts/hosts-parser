<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser\Exception;

use Throwable;

/**
 * Class HostsFileException
 *
 * @package NorthernLights\HostsFileParser\Exception
 */
class HostsFileException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
