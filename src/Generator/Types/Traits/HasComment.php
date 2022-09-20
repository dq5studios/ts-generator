<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Types\Traits;

use DQ5Studios\TypeScript\Generator\Tokens\CommentToken;

/**
 * Attaches a comment
 */
trait HasComment
{
    protected CommentToken|null $comment = null;

    public function addComment(string $comment): CommentToken
    {
        if (!isset($this->comment)) {
            $this->comment = new CommentToken($comment);
        } else {
            $this->comment->expand($comment);
        }

        return $this->comment;
    }

    public function getComment(): CommentToken
    {
        if (is_null($this->comment)) {
            $this->comment = new CommentToken();
        }

        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        if (!isset($this->comment)) {
            $this->comment = new CommentToken($comment);
        } else {
            $this->comment->set($comment);
        }

        return $this;
    }
}
