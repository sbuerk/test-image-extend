#!/usr/bin/env php
<?php

$path = realpath(__DIR__);

$imageName = ($argv[1] ?? '');
$action = ($argv[2] ?? '--patchlevel');

$images = [];
$imagesFile = $path . '/images.json';
if (file_exists($imagesFile)) {
    $images = json_decode((string)file_get_contents($imagesFile), true, JSON_THROW_ON_ERROR);
}
if (!is_array($images)) {
    $images = [];
}
if ($imageName === '') {
    echo "No image name provided. Use ./increment-tag.php <image-name>" . PHP_EOL;
    foreach(array_keys($images) as $imageName) {
        echo " * $imageName" . PHP_EOL;
    }
    echo PHP_EOL;
    exit(1);
}
if (!is_array($images[$imageName] ?? false)) {
    echo "Could not find image entry for \"\". Please add it initially to images.json" . PHP_EOL;
    exit(1);
}
if (!in_array($action, ['--patchlevel', '--minor'])) {
    echo "Invalid version increment type selected \"$action\". Allowed: --patchlevel, --minor" . PHP_EOL;
    exit(1);
}

$major = 1;
$minor = 0;
$patchlevel = 0;
$fullOld = '';
if ($images[$imageName] ?? false) {
    $major = (int)($images[$imageName]['major'] ?? 1);
    $minor = (int)($images[$imageName]['minor'] ?? 0);
    $patchlevel = (int)($images[$imageName]['patchlevel'] ?? 0);

    if ($action === '--patchlevel') {
        $patchlevel++;
    }
    if ($action === '--minor') {
        $minor++;
        $patchlevel = 0;
    }
    $fullOld = $images[$imageName]['full'] ?? '';
} else {
    echo "Could not find image entry for \"\". Please add it initially to images.json" . PHP_EOL;
    exit(1);
}

$images[$imageName]['major'] = $major;
$images[$imageName]['minor'] = $minor;
$images[$imageName]['patchlevel'] = $patchlevel;
$images[$imageName]['full'] = implode('.', [$major, $minor, $patchlevel]);
$images[$imageName]['short'] = implode('.', [$major, $minor]);
$fullNew = $images[$imageName]['full'] ?? '';
ksort($images[$imageName]);
ksort($images);
file_put_contents($imagesFile, json_encode($images, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

echo "---------------------------------------------" . PHP_EOL;
echo " IMAGE...: $imageName" . PHP_EOL;
echo " OLD.: $fullOld" . PHP_EOL;
echo " NEW.: $fullNew" . PHP_EOL;
echo "---------------------------------------------" . PHP_EOL;
echo PHP_EOL;