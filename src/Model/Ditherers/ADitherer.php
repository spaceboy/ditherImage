<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

use GdImage;

/**
 * Parent of ditherers.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 */
abstract class ADitherer
{
    /** @var array<int, array<int, int>> Image colors map. */
    protected array $map = [];

    protected int $height;

    protected int $width;

    /** @var int Initial margin color. */
    private const INIT_COLOR = 0;

    /**
     * Konstruktor.
     *
     * @param GdImage $image
     */
    public function __construct(protected GdImage $image)
    {
        $this->width = imagesx($image);
        $this->height = imagesy($image);
    }

    /**
     * Dithering method.
     *
     * @return GdImage
     */
    public function dither(): GdImage
    {
        $image = $this->image;

        imagefilter($image, IMG_FILTER_GRAYSCALE);

        $width = $this->width;
        $height = $this->height;

        $widthLimit = $width + 5;
        $heightLimit = $height + 5;

        for ($y = -5; $y < 0; ++$y) {
            $this->map[$y] = array_fill(-5, $widthLimit + 5, self::INIT_COLOR);
        }
        for ($y = 0; $y < $height; ++$y) {
            $this->map[$y] = array_fill(-5, 5, self::INIT_COLOR);
            for ($x = 0; $x < $width; ++$x) {
                $this->map[$y][$x] = imagecolorat($image, $x, $y) & 0xFF;
            }
            for ($x = $width; $x < $widthLimit; ++$x) {
                $this->map[$y][$x] = self::INIT_COLOR;
            }
        }
        for ($y = $height; $y < $heightLimit; ++$y) {
            $this->map[$y] = array_fill(-5, $widthLimit + 5, self::INIT_COLOR);
        }

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $old = min(255, $this->map[$y][$x]);
                // TODO: Nějaký threshold?
                $new = ($old >> 7) << 7;

                $this->map[$y][$x] = $new;

                $error = $old - $new;
                $this->ditherPixel($x, $y, $error);
            }
        }

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $gray = $this->map[$y][$x];
                // TODO: Lepší výběr barev (imagecolorallocate - $black / $white)?
                $rgb = ($gray << 16) + ($gray << 8) + $gray;
                imagesetpixel($image, $x, $y, $rgb);
            }
        }

        return $image;
    }

    /**
     * Dithering method.
     *
     * @param int $x
     * @param int $y
     * @param int $error
     *
     * @return void
     */
    abstract protected function ditherPixel(int $x, int $y, int $error): void;
}
