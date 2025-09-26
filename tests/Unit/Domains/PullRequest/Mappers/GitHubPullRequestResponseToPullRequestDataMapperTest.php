<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\PullRequest\Mappers;

use App\Domains\PullRequests\Mappers\GitHubPullRequestResponseToPullRequestDataMapper;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;
use Carbon\CarbonImmutable;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(GitHubPullRequestResponseToPullRequestDataMapper::class)]
final class GitHubPullRequestResponseToPullRequestDataMapperTest extends TestCase
{
    private GitHubPullRequestResponseToPullRequestDataMapper $mapper;

    #[Override]
    protected function setUp(): void
    {
        $this->mapper = new GitHubPullRequestResponseToPullRequestDataMapper();
    }

    #[Test]
    public function minimumData_map_returnsPullRequestData(): void
    {
        // Given
        $input = [
            'user' => [
                'login' => 'muh_username',
                'html_url' => 'https://example.com/muh_username',
            ],
            'base' => [
                'ref' => '12.x',
            ],
            'number' => 54321,
            'title' => 'Muh Pull Request',
            'body' => 'Muh Description',
            'html_url' => 'https://example.com/pull-request-url',
            'merged_at' => '2024-12-31 12:31:30',
        ];

        // When
        $result = $this->mapper->map($input);

        // Then
        self::assertInstanceOf(PullRequestData::class, $result);
        self::assertInstanceOf(AuthorData::class, $result->author);
        self::assertInstanceOf(CarbonImmutable::class, $result->mergedAt);
        self::assertSame('muh_username', $result->author->username);
        self::assertSame('https://example.com/muh_username', $result->author->profileUrl);
        self::assertSame('12.x', $result->targetBranchName);
        self::assertSame(54321, $result->number);
        self::assertSame('Muh Pull Request', $result->title);
        self::assertSame('Muh Description', $result->body);
        self::assertSame('https://example.com/pull-request-url', $result->url);
        self::assertEquals('2024-12-31 12:31:30', $result->mergedAt); // loose comparison is fine
    }
}
