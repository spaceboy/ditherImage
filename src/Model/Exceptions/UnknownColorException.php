<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

/**
 * "Unknown color" exception.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class UnknownColorException extends ADitherImageException
{
    /** @inheritDoc */
    protected const MESSAGE = 'Unknown color (%s).';
}
