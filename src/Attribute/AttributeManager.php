<?php

namespace App\Attribute;

use Exception;

class AttributeManager
{
    /**
     * @throws Exception
     */
    public function getPhpFileNamesFromDir(
        string $directory,
        array  $exclude = [],
    ): array|false
    {
        $files = array_diff(
            scandir($directory),
            ['.', '..', ...$exclude]
        );

        if (!$files)
            throw new Exception('The scanned item is not a directory');

        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = str_replace('.php', '', $file);
        }

        return $fileNames;
    }
}