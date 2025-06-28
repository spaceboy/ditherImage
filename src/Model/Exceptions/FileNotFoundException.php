<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

/**
 * "File not found" exception.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 */
final class FileNotFoundException extends ADitherImageException
{
    /** @inheritDoc */
    protected const MESSAGE = 'File (%s) not found or not readable.';
}
