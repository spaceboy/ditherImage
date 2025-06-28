<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

/**
 * "Can't allocate color" exception.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class ColorException extends ADitherImageException
{
    /** @inheritDoc */
    protected const MESSAGE = 'Can\'t allocate color.';
}
