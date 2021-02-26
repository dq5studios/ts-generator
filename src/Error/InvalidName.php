<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Error;

use Error;

class InvalidName extends Error
{
    /** @var string */
    protected $message = "Invalid Name";
    /** @var int */
    protected $code = 1;
}
