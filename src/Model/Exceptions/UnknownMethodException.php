<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

/**
 * "Unknown dithering method" exception.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 */
final class UnknownMethodException extends ADitherImageException
{
    /** @inheritDoc */
    protected const MESSAGE = 'Unknown dithering method (%d).';
}
