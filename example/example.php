<?php declare(strict_types = 1);

/**
 * Example of image dithering.
 *
 * @author Spaceboy <jiri.votocek@centrum.cz>
 * @licence MIT
 */

use Spaceboy\DitherImage\DitherImage;

require_once(__DIR__ . '/../vendor/autoload.php');

$imageFileName = ($argv[1] ?? __DIR__ . '/test01.jpg');

foreach (
    [
        DitherImage::ATKINSON => 'atkinson',
        DitherImage::BURKES => 'burkes',
        DitherImage::FLOYD_STEINBERG => 'floyd-steinberg',
        DitherImage::JARVIS => 'jarvis',
        DitherImage::SIERRA2 => 'sierra2',
        DitherImage::SIERRA2_4A => 'sierra2-4a',
        DitherImage::SIERRA3 => 'sierra3',
        DitherImage::STUCKI => 'stucki',
    ] as $method => $name
) {
    echo "Processing {$name} method.";
    imagepng(
        DitherImage::fromJpeg($imageFileName)->setThreshold(5)->setBlack('f00')->setWhite('ff0')->dither($method),
        "{$imageFileName}-{$name}.png"
    );
    echo ' Done.' . PHP_EOL;
}
