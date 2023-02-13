<?php

declare(strict_types=1);

namespace App\Service\File;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class FtpFilerUploader implements UploaderInterface
{
    public function __construct(private readonly ContainerBagInterface $bag)
    {
    }

    public function upload(string $sourceFile, string $newFile): void
    {
        $connect = $this->doConnect();
        \ftp_put ($connect, $newFile, $sourceFile, \FTP_ASCII);
        $this->doDisconnect($connect);
    }

    public function list(string $directoryName): array
    {
        $connect = $this->doConnect();
        $files = \ftp_nlist($connect, "/");
        $this->doDisconnect($connect);

        return $files;
    }

    private function doConnect()
    {
        $hostname = $this->bag->get('ftp.host');
        $connect = \ftp_connect($hostname);
        $user = $this->bag->get('ftp.username');
        $password= $this->bag->get('ftp.password');
        \ftp_login($connect, $user, $password);

        return $connect;
    }

    private function doDisconnect($connect)
    {
        return  \ftp_close($connect);
    }
}
