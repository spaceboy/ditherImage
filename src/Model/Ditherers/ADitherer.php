<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage\Model\Ditherers;

use GdImage;
use Spaceboy\DitherImage\Model\Exceptions\IncorrectImageException;
use Spaceboy\DitherImage\Model\RgbColor;

/**
 * Parent of ditherers.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
abstract class ADitherer
{
    /** @var array<int, array<int, int>> Image colors map. */
    protected array $map = [];

    /** @var int Image height. */
    protected int $height;

    /** @var int Image width. */
    protected int $width;

    /** @var int Initial margin color. */
    private const INIT_COLOR = 0;

    /**
     * Konstruktor.
     *
     * @param GdImage $image
     *
     * @throws IncorrectImageException
     */
    public function __construct(protected GdImage $image)
    {
        $width = imagesx($image);
        /** @phpstan-ignore identical.alwaysFalse */
        if ($width === false) {
            IncorrectImageException::throw();
        }

        $height = imagesy($image);
        /** @phpstan-ignore identical.alwaysFalse */
        if ($height === false) {
            IncorrectImageException::throw();
        }

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Dithering method.
     *
     * @param int      $threshold
     * @param RgbColor $black
     * @param RgbColor $white
     *
     * @return GdImage
     */
    public function dither(int $threshold, RgbColor $black, RgbColor $white): GdImage
    {
        $image = $this->image;

        imagefilter($image, IMG_FILTER_GRAYSCALE);

        $width = $this->width;
        $height = $this->height;

        $widthLimit = $width + 5;
        $heightLimit = $height + 5;

        $blackColor = $black->colorAllocate($this->image);
        $whiteColor = $white->colorAllocate($this->image);

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
                // TODO: Threshold sem?
                $old = min(255, $this->map[$y][$x]);
                // TODO: Nebo threshold sem?
                $new = ($old >> 7) << 7;
                $this->map[$y][$x] = $new;
                $error = $old - $new;
                $this->ditherPixel($x, $y, $error);
            }
        }

        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                // TODO: A/nebo threshold sem?
                imagesetpixel($image, $x, $y, ($this->map[$y][$x] <= $threshold ? $blackColor : $whiteColor));
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
