<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

/**
 * Implementation of Bill Atkinson dithering.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class DithererAtkinson extends ADitherer
{
    /** @inheritDoc */
    protected function ditherPixel(int $x, int $y, int $error): void
    {
        $diff  = (int) round((1/8) * $error);

        $this->map[$y    ][$x + 1] += $diff;
        $this->map[$y    ][$x + 2] += $diff;
        $this->map[$y + 1][$x - 1] += $diff;
        $this->map[$y + 1][$x    ] += $diff;
        $this->map[$y + 1][$x + 1] += $diff;
        $this->map[$y + 2][$x    ] += $diff;
    }
}
