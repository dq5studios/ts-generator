<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Interfaces;

use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;

/**
 * Attaches a comment
 */
interface CanComment
{
    public function addComment(string $comment): CommentToken;
    public function getComment(): CommentToken;
    public function setComment(string $comment): self;
}
