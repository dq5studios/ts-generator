<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

/**
 * A comment for the item below it
 */
class CommentToken
{
    public function __construct(protected string $comment = "")
    {
    }

    public function expand(string $comment): self
    {
        $this->comment .= "\n{$comment}";

        return $this;
    }

    public function get(): string
    {
        return $this->comment;
    }

    public function set(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
