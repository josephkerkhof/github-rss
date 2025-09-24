<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Enums;

enum IssueFilter: string
{
    case IS_PR = 'is:pr';
    case IS_MERGED = 'is:merged';
}
