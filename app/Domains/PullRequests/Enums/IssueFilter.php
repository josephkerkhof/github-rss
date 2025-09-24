<?php

namespace App\Domains\PullRequests\Enums;

enum IssueFilter: string
{
    case IS_PR = 'is:pr';
    case IS_MERGED = 'is:merged';
}
