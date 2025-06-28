<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Parent of package exceptions.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
abstract class ADitherImageException extends RuntimeException
{
    /** @var string Inheritable error message. */
    protected const MESSAGE = '';

    /**
    * Constructor.
    *
    * @param string         $message
    * @param int            $code
    * @param Throwable|null $previous
    */
    final public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
    * Throws exception.
    *
    * @param bool|float|int|string|null ...$params
    *
    * @return no-return
    * @throws ADitherImageException
    */
    public static function throw(bool|float|int|string|null ...$params): void
    {
        throw new static(sprintf(static::MESSAGE, ...$params));
    }
}
