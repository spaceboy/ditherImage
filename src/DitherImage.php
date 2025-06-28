<?php declare(strict_types = 1);

namespace Spaceboy\DitherImage;

use GdImage;
use Spaceboy\DitherImage\Model\Ditherers\DithererAtkinson;
use Spaceboy\DitherImage\Model\Ditherers\DithererBurkes;
use Spaceboy\DitherImage\Model\Ditherers\DithererFloydSteinberg;
use Spaceboy\DitherImage\Model\Ditherers\DithererJarvis;
use Spaceboy\DitherImage\Model\Ditherers\DithererSierra2;
use Spaceboy\DitherImage\Model\Ditherers\DithererSierra24a;
use Spaceboy\DitherImage\Model\Ditherers\DithererSierra3;
use Spaceboy\DitherImage\Model\Ditherers\DithererStucki;
use Spaceboy\DitherImage\Model\Exceptions\ADitherImageException;
use Spaceboy\DitherImage\Model\Exceptions\FileNotFoundException;
use Spaceboy\DitherImage\Model\Exceptions\IncorrectImageException;
use Spaceboy\DitherImage\Model\Exceptions\UnknownMethodException;
use Spaceboy\DitherImage\Model\RgbColor;

/**
 * Image dithering tool.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */
final class DitherImage
{
    /** @var int Bill Atkinson dithering. */
    public const ATKINSON = 1;

    /** @var int Burkes dithering. */
    public const BURKES = 2;

    /** @var int Floyd-Steinberg dithering. */
    public const FLOYD_STEINBERG = 3;

    /** @var int Jarvis-Judice-Ninke dithering. */
    public const JARVIS = 4;

    /** @var int Sierra 2 dithering. */
    public const SIERRA2 = 5;

    /** @var int Sierra 2-4a dithering. */
    public const SIERRA2_4A = 6;

    /** @var int Sierra 3 dithering. */
    public const SIERRA3 = 7;

    /** @var int Stucki dithering. */
    public const STUCKI = 8;

    /** @var RgbColor|null Black color. */
    private ?RgbColor $colorBlack = null;

    /** @var RgbColor|null White color. */
    private ?RgbColor $colorWhite = null;

    /** @var int Threshold (black/white). */
    private int $threshold = 127;

    /** @var string Default black color. */
    private const DEFAULT_BLACK = '000000';

    /** @var string Default white color. */
    private const DEFAULT_WHITE = 'ffffff';

    /**
     * Constructor.
     * Create instance using static functions {@see self::fromJpeg()}, {@see self::fromPng()}, {@see self::fromString()}...
     *
     * @param GdImage $image
     */
    private function __construct(private GdImage $image)
    {
    }

    /**
     * Open JPEG image from filename.
     *
     * @param string $fileName
     *
     * @return self
     * @throws ADitherImageException
     */
    public static function fromJpeg(string $fileName): self
    {
        self::checkImageFile($fileName);
        return new self(self::checkImage(@imagecreatefromjpeg($fileName)));
    }

    /**
     * Open PNG image from filename.
     *
     * @param string $fileName
     *
     * @return self
     * @throws ADitherImageException
     */
    public static function fromPng(string $fileName): self
    {
        self::checkImageFile($fileName);
        return new self(self::checkImage(@imagecreatefrompng($fileName)));
    }

    /**
     * Open image from image string.
     *
     * @param string $imageString
     *
     * @return self
     * @throws ADitherImageException
     */
    public static function fromString(string $imageString): self
    {
        return new self(self::checkImage(@imagecreatefromstring($imageString)));
    }

    /**
     * Dithers image.
     *
     * @param int $method
     *
     * @return GdImage
     * @throws ADitherImageException
     */
    public function dither(int $method = self::FLOYD_STEINBERG): GdImage
    {
        $ditherer = match ($method)
        {
            self::ATKINSON => new DithererAtkinson($this->image),
            self::BURKES => new DithererBurkes($this->image),
            self::FLOYD_STEINBERG => new DithererFloydSteinberg($this->image),
            self::JARVIS => new DithererJarvis($this->image),
            self::SIERRA2 => new DithererSierra2($this->image),
            self::SIERRA2_4A => new DithererSierra24a($this->image),
            self::SIERRA3 => new DithererSierra3($this->image),
            self::STUCKI => new DithererStucki($this->image),
            default => UnknownMethodException::throw($method),
        };

        return $ditherer->dither(
            $this->threshold,
            $this->colorBlack ?? new RgbColor(self::DEFAULT_BLACK),
            $this->colorWhite ?? new RgbColor(self::DEFAULT_WHITE)
        );
    }

    /**
     * Set color for "black" (e.g. "000" or "#000000").
     *
     * @param string $color
     *
     * @return self
     */
    public function setBlack(string $color): self
    {
        $this->colorBlack = new RgbColor($color);

        return $this;
    }

    /**
     * Set threshold (0-255); lower => lighter, higher => darker.
     *
     * @param int $threshold
     *
     * @return self
     */
    public function setThreshold(int $threshold): self
    {
        $this->threshold = $threshold;

        return $this;
    }

    /**
     * Set color for "white" (e.g. "fff" or "#ffffff").
     *
     * @param string $color
     *
     * @return self
     */
    public function setWhite(string $color): self
    {
        $this->colorWhite = new RgbColor($color);

        return $this;
    }

    /**
     * Throws exception when file is not readable.
     *
     * @param string $fileName
     *
     * @return void
     * @throws ADitherImageException
     */
    private static function checkImageFile(string $fileName): void
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
            FileNotFoundException::throw($fileName);
        }
    }

    /**
     * Throws exception when image was not opened correctly.
     *
     * @param GdImage|false $image
     *
     * @return GdImage
     * @throws ADitherImageException
     */
    private static function checkImage(GdImage|false $image): GdImage
    {
        if ($image === false) {
            IncorrectImageException::throw();
        }

        return $image;
    }
}
