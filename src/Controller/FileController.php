<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\File\UploaderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FileController
{
    public function __construct(
        #[Autowire(service: 'app.ftp_uploader')]
        private readonly UploaderInterface $uploader,
        #[Autowire('%kernel.project_dir%/public')]
        private readonly string $publicDir
    ) {
    }

    #[Route("/files", name: "app_files")]
    public function index(): Response
    {
        // uncomment the lines bellow when using the FTP uploader
        /*$this->uploader->upload('/var/www/html/simple.txt', '/simple.txt');
        $files = $this->uploader->list('/');*/

        // uncomment the lines bellow when using the Filesystem uploader
        /*$this->uploader->upload('/var/www/html/simple.txt', '/gpfs/simple.txt');
        $files = $this->uploader->list('/gpfs');*/

        return new Response();
    }
}