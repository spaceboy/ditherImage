<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Exceptions;

/**
 * "Incorrect image" exception".
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class IncorrectImageException extends ADitherImageException
{
    /** @inheritDoc */
    protected const MESSAGE = 'Error while creating or reading image.';
}
