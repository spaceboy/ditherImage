<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

/**
 * Implementation of Burkes dithering.
 */
final class DithererBurkes extends ADitherer
{
    /** @inheritDoc */
    protected function ditherPixel(int $x, int $y, int $error): void
    {
        $diff  = (int) round((1/32) * $error);

        $this->map[$y    ][$x + 1] += $diff * 8;
        $this->map[$y    ][$x + 2] += $diff * 4;
        $this->map[$y + 1][$x - 2] += $diff * 2;
        $this->map[$y + 1][$x - 1] += $diff * 4;
        $this->map[$y + 1][$x    ] += $diff * 8;
        $this->map[$y + 1][$x + 1] += $diff * 4;
        $this->map[$y + 1][$x + 2] += $diff * 2;
    }
}
