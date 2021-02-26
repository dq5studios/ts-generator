<?php

declare(strict_types=1);

namespace DQ5Studios\TypeScript\Generator\Tokens;

/**
 * A comment for the item below it
 */
class CommentToken
{
    public function __construct(protected string $comment)
    {
    }

    public function addComment(string $comment): self
    {
        $this->comment .= "\n" . $comment;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function __toString(): string
    {
        $comments = trim($this->comment);
        if (empty($comments)) {
            return "";
        }
        if (empty(preg_match_all("/\n/", $comments))) {
            return "/** {$comments} */";
        }
        $comments = preg_replace("/\n/", "\n * ", $comments);
        return "/**\n * {$comments}\n */";
    }
}
