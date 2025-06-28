<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

/**
 * Implementation of Sierra 2-4a dithering.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class DithererSierra24a extends ADitherer
{
    /** @inheritDoc */
    protected function ditherPixel(int $x, int $y, int $error): void
    {
        $diff  = (int) round((1/4) * $error);

        $this->map[$y    ][$x + 1] += $diff * 2;
        $this->map[$y + 1][$x - 1] += $diff * 1;
        $this->map[$y + 1][$x    ] += $diff * 1;
    }
}
