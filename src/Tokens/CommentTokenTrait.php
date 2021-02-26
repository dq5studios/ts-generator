<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

/**
 * Attaches a comment
 */
trait CommentTokenTrait
{
    private CommentToken $comment;

    public function addComment(string $comment): self
    {
        if (!isset($this->comment)) {
            $this->comment = new CommentToken($comment);
        } else {
            $this->comment->addComment($comment);
        }
        return $this;
    }

    public function getComment(): CommentToken
    {
        if (!isset($this->comment)) {
            $this->comment = new CommentToken("");
        }
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        if (!isset($this->comment)) {
            $this->comment = new CommentToken($comment);
        } else {
            $this->comment->setComment($comment);
        }
        return $this;
    }
}
