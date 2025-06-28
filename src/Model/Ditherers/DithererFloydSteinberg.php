<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

/**
 * Implementation of Floyd-Steinberg dithering.
 */
final class DithererFloydSteinberg extends ADitherer
{
    /** @inheritDoc */
    protected function ditherPixel(int $x, int $y, int $error): void
    {
        $diff  = (int) round((1/16) * $error);

        $this->map[$y    ][$x + 1] += $diff * 7;
        $this->map[$y + 1][$x - 1] += $diff * 3;
        $this->map[$y + 1][$x    ] += $diff * 5;
        $this->map[$y + 1][$x + 1] += $diff;
    }
}
