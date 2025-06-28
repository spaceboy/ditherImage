<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

/**
 * Implementation of Sierra 2 dithering.
 */
final class DithererSierra2 extends ADitherer
{
    /** @inheritDoc */
    protected function ditherPixel(int $x, int $y, int $error): void
    {
        $diff  = (int) round((1/16) * $error);

        $this->map[$y    ][$x + 1] += $diff * 4;
        $this->map[$y    ][$x + 2] += $diff * 3;
        $this->map[$y + 1][$x - 2] += $diff * 1;
        $this->map[$y + 1][$x - 1] += $diff * 2;
        $this->map[$y + 1][$x    ] += $diff * 3;
        $this->map[$y + 1][$x + 1] += $diff * 2;
        $this->map[$y + 1][$x + 2] += $diff * 1;
    }
}
