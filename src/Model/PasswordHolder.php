<?php

declare(strict_types=1);

namespace App\Model;

use App\Validator\PasswordConstraint;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class PasswordHolder
{
    #[UserPassword]
    private string $oldPassword;

    #[PasswordConstraint]
    private string $newPassword;

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
