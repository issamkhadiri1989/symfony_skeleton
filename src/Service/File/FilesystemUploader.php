<?php

declare(strict_types=1);

namespace App\Service\File;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FilesystemUploader implements UploaderInterface
{
    private LoggerInterface $logger;

    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function upload(string $sourceFile, string $newFile): void
    {
        if ($this->filesystem->exists($sourceFile)) {
            if ($this->filesystem->exists($newFile)) {
                $this->filesystem->remove($newFile);
            }
            $this->filesystem->rename($sourceFile, $newFile);
        }
    }

    public function list(string $directoryName): array
    {
        $finder = new Finder();
        $files = $finder->files()->in($directoryName);

        return $this->doExtractFiles($files->getIterator());
    }

    private function doExtractFiles(\Iterator $files): array
    {
        $elements = \iterator_to_array($files);
        \array_walk($elements, function (SplFileInfo &$item) {
            $item = $item->getFilename();
        });

        return \array_values($elements);
    }

    // uncomment the line bellow to enable autowiring
    //#[Required]
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}