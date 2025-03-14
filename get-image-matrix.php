#!/usr/bin/env php
<?php
$path = realpath(__DIR__);
$imagesFile = $path . '/images.json';
$result = [];

try {
    $images = \json_decode((string)file_get_contents($imagesFile), true, JSON_THROW_ON_ERROR);

    foreach($images as $image) {
        $item = [
            "lookup-name" => $image['lookup-name'] ?? '',
            "gh-image-basename" => $image['gh-image-basename'] ?? '',
            "gh-image-basetag" => $image['gh-image-basetag'] ?? 'latest',
            "gh-image-name" => $image['gh-image-name'] ?? '',
            "context" => $image['context'] ?? '',
            "version-full" => $image['full'] ?? '',
            "version-short" => $image['short'] ?? '',
            "platforms" => $image['platforms'] ?? '',
        ];

        if ($item['lookup-name'] === ''
            || $item['gh-image-basename'] === ''
            || $item['gh-image-name'] === ''
            || $item['version-full'] === ''
            || $item['version-short'] === ''
            || $item['context'] === ''
            || $item['platforms'] === ''
            || !file_exists(__DIR__ . '/' . $item['context'] . '/Dockerfile')
        ) {
            // skip - invalid entry
            continue;
        }

        $result['include'][] = $item;
    }

} catch(\Throwable) {}
//echo str_replace('"', '\"', \json_encode($result, JSON_THROW_ON_ERROR));
echo \json_encode($result, JSON_THROW_ON_ERROR);