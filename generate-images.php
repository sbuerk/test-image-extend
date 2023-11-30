#!/usr/bin/env php
<?php
$path = realpath(__DIR__);
chdir($path);
$imagesFile = $path . '/images.json';
$images = json_decode(file_get_contents($imagesFile), true, JSON_THROW_ON_ERROR);

function ensureImageDockerFile(
    string $imageFolder,
    string $imageName,
    string $templateFile,
    string $dockerFileName,
): ?string {
    if (!is_file($templateFile)) {
        return null;
    }
    $replaces = [
        '%%FROM_IMAGE%%' => 'ghcr.io/typo3/' . $imageName,
    ];
    $content = file_get_contents($templateFile);
    $content = str_replace(
        array_keys($replaces),
        array_values($replaces),
        $content
    );
    $old = file_exists($dockerFileName) ? file_get_contents($dockerFileName) : '';
    file_put_contents($dockerFileName, $content);
    $result = $old === $content ? 'unchanged' : ($old === '' ? 'new' : 'changed');
    echo " >> Generated \"$dockerFileName\" from \"$templateFile\" [$result]" . PHP_EOL;
    return $result;
}

function ensureTYPO3Repository(string $basePath, array &$images): void
{
    $currentDirectory = getcwd();
    $repoParentPath = $basePath . '/tmp';
    $repoBaseName = 'testing-infrastructure';
    $repoPath = $repoParentPath . '/' . $repoBaseName;
    if (!is_file($repoPath . '/.git/config')) {
        if (is_dir($repoPath)) {
            `rm -Rf $repoPath`;
        }
        if (!is_dir($repoParentPath)) {
            @mkdir($repoParentPath, 0777, true);
        }
        chdir($repoParentPath);
        `git clone https://git.typo3.org/typo3/CI/testing-infrastructure.git/ ./$repoBaseName`;
    }

    chdir($repoPath);
    `git checkout main`;
    `git pull --rebase`;
    chdir($repoPath . '/docker-images');
    $folders = glob(
        "core-testing-php??",
        GLOB_ONLYDIR | GLOB_BRACE
    );
    chdir($currentDirectory);

    foreach ($folders as $baseImageName) {
        if (!is_dir($basePath . '/' . $baseImageName)) {
            @mkdir($basePath . '/' . $baseImageName);
        }

        $dockerFileName = $basePath . '/' . $baseImageName . '/Dockerfile';
        $templateFile = $basePath . '/templates/Dockerfile-core-testing-phpXY';
        if (file_exists($basePath . '/templates/Dockerfile-' . $baseImageName)) {
            $templateFile = $basePath . '/templates/Dockerfile-' . $baseImageName;
        }
        $changed = ensureImageDockerFile(
            $basePath . '/' . $baseImageName,
            $baseImageName,
            $templateFile,
            $dockerFileName,
        );
        if ($changed !== 'unchanged' && file_exists($dockerFileName)) {
            echo " >> $changed image configuration for $baseImageName" . PHP_EOL;
            $image = $images[$baseImageName] ?? [
                "context" => $baseImageName,
                "full" => "1.0.0",
                "gh-image-basename" => "ghcr.io/typo3/$baseImageName",
                "gh-image-name" => "ghcr.io/sbuerk/demo-$baseImageName",
                "lookup-name" => $baseImageName,
                "major" => 1,
                "minor" => 0,
                "patchlevel" => 0,
                "platforms" => "linux/amd64,linux/arm64",
                "short" => "1.0",
            ];
            if ($changed === "changed") {
                $image['patchlevel']++;
                $image['full'] = implode('.', [$image['major'], $image['minor'], $image['patchlevel']]);
                $image['short'] = implode('.', [$image['major'], $image['minor']]);
            }
            ksort($image);
            $images[$baseImageName] = $image;
            ksort($images);
        }
    }
}

ensureTYPO3Repository($path, $images);
ksort($images);
$written = file_put_contents($imagesFile, \json_encode($images, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
echo " >> Updates $imagesFile - written $written bytes" . PHP_EOL;
echo PHP_EOL;