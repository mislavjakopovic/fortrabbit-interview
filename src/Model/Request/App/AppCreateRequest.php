<?php

declare(strict_types=1);

namespace App\Model\Request\App;

use App\Model\Request\RequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AppCreateRequest implements RequestInterface
{
    #[Assert\NotBlank]
    public string $name;
}
