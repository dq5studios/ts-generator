<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Tests;

use DQ5Studios\TypeScript\Generator\Attributes\IsInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;

/**
 * An example class
 *
 * @property float $doc_only
 */
#[IsInterface]
class ClassTestInterface
{
    public string $key = "name";
    /** @var (string|int)[] All the names */
    public string|array $list = ["name", "scram"];
    /** @var int Special value */
    protected $value = 42;
    public PhpDocExtractor|null $ex = null;
    /** @var array<string,string|int> */
    private array $surprise = ["a1" => 1, "b2" => 2, "c3" => 3];
}
