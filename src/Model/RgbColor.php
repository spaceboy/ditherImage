<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model;

use GdImage;
use Spaceboy\DitherImage\Model\Exceptions\ColorException;
use Spaceboy\DitherImage\Model\Exceptions\UnknownColorException;

/**
 * Representation of RGB color.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class RgbColor
{
    /** @var int<0, 255> Red color. */
    private int $red;

    /** @var int<0, 255> Green color. */
    private int $green;

    /** @var int<0, 255> Blue color. */
    private int $blue;

    /**
     * Constructor.
     *
     * @param string $color Color in string format, e.g. "#000", "102030"...
     *
     * @throws UnknownColorException
     */
    public function __construct(string $color)
    {
        $rgbColor = $color;

        if ($rgbColor[0] === '#') {
            $rgbColor = substr($rgbColor, 1);
        }

        if (strlen($rgbColor) === 3) {
            $rgbColor = str_repeat($rgbColor[0], 2) . str_repeat($rgbColor[1], 2) . str_repeat($rgbColor[2], 2);
        }

        if (strlen($rgbColor) !== 6) {
            UnknownColorException::throw($color);
        }

        /** @phpstan-ignore assign.propertyType */
        $this->red = (int) hexdec(substr($rgbColor, 0, 2));

        /** @phpstan-ignore assign.propertyType */
        $this->green = (int) hexdec(substr($rgbColor, 2, 2));

        /** @phpstan-ignore assign.propertyType */
        $this->blue = (int) hexdec(substr($rgbColor, 4, 2));
    }

    /**
     * Allocate color on given image.
     *
     * @param GdImage $image
     *
     * @return int
     * @throws ColorException
     */
    public function colorAllocate(GdImage $image): int
    {
        $color = @imagecolorallocate($image, $this->red, $this->green, $this->blue);
        if ($color === false) {
            ColorException::throw();
        }

        return $color;
    }
}
