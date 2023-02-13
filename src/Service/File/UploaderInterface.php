<?php

declare(strict_types=1);

namespace App\Service\File;

interface UploaderInterface
{
    /**
     * Performs upload files.
     *
     * @param string $sourceFile
     * @param string $newFile
     */
    public function upload(string $sourceFile, string $newFile): void;

    /**
     * List the content of the given directory.
     *
     * @param string $directoryName
     */
    public function list(string $directoryName): array;
}
