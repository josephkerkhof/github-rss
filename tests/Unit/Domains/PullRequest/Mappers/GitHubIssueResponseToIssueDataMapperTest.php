<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\PullRequest\Mappers;

use App\Domains\PullRequests\Mappers\GitHubIssueResponseToIssueDataMapper;
use App\Domains\PullRequests\Schema\IssueData;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GitHubIssueResponseToIssueDataMapper::class)]
final class GitHubIssueResponseToIssueDataMapperTest extends TestCase
{
    private GitHubIssueResponseToIssueDataMapper $mapper;

    #[Override]
    protected function setUp(): void
    {
        $this->mapper = new GitHubIssueResponseToIssueDataMapper();
    }

    #[Test]
    public function minimumData_map_returnsIssueData(): void
    {
        // Given
        $input = [
            'number' => 5,
            'pull_request' => [
                'html_url' => 'https://example.com',
            ]
        ];

        // When
        $result = $this->mapper->map($input);

        // Then
        self::assertInstanceOf(IssueData::class, $result);
        self::assertSame(5, $result->number);
        self::assertSame('https://example.com', $result->htmlUrl);
    }
}
