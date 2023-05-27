<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . "/src",
    ]);

    $rectorConfig->cacheDirectory("./cache/rector");

    // register a single rule
    $rectorConfig->rule(ChangeSwitchToMatchRector::class);
    $rectorConfig->rule(StrContainsRector::class);
    $rectorConfig->rule(StrEndsWithRector::class);
    $rectorConfig->rule(StrStartsWithRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->skip([
        EncapsedStringsToSprintfRector::class,
        VarConstantCommentRector::class,
        CallableThisArrayToAnonymousFunctionRector::class,
        NewlineAfterStatementRector::class,
        UnSpreadOperatorRector::class,
    ]);
};
