<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\File\FilesystemUploader;
use App\Service\File\FtpFilerUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FileController
{
    #[Route("/files", name: "app_files")]
    public function index(FtpFilerUploader $ftpFilerUploader, FilesystemUploader $filesystemUploader): Response
    {
        $ftpFilerUploader->upload('/var/www/html/simple.txt', '/simple.txt');
        $files = $ftpFilerUploader->list('/');

        $filesystemUploader->upload('/var/www/html/simple.txt', '/gpfs/simple.txt');
        $files = $filesystemUploader->list('/gpfs');

        return new Response();
    }
}