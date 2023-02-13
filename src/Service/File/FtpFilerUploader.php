<?php

declare(strict_types=1);

namespace App\Service\File;

class FtpFilerUploader implements UploaderInterface
{
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
        $connect = \ftp_connect("my-ftp-server") or die ("Connection to server unsuccessful");
        $user = "user";
        $password= "123";
        \ftp_login($connect, $user, $password) or die ("Login was unsuccessful");

        return $connect;
    }

    private function doDisconnect($connect)
    {
        return  \ftp_close($connect);
    }
}
