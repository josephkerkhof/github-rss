<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Repositories\Mappers;

use App\Domains\Repositories\Mappers\PullRequestToResponseMapper;
use App\Domains\Repositories\Schema\Responses\PullRequestData;
use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(PullRequestToResponseMapper::class)]
final class PullRequestToResponseMapperTest extends TestCase
{
    private PullRequestToResponseMapper $mapper;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();

        $this->mapper = new PullRequestToResponseMapper();
    }

    #[Test]
    public function minimumData_map_returnsPullRequestData(): void
    {
        // Given
        $author = Author::factory()->make([
            'username' => 'test_user',
            'profile_url' => 'https://example.com/test_user',
        ]);

        $branch = Branch::factory()->make([
            'name' => 'main',
        ]);

        $pullRequest = PullRequest::factory()->make([
            'title' => 'Test Pull Request',
            'body' => 'This is a test PR',
            'number' => 42,
            'url' => 'https://example.com/pull/42',
            'merged_at' => '2024-12-31 12:31:30',
        ]);
        $pullRequest->setRelation('branch', $branch);
        $pullRequest->setRelation('author', $author);

        // When
        $result = $this->mapper->map($pullRequest);

        // Then
        self::assertInstanceOf(PullRequestData::class, $result);
        self::assertSame('Test Pull Request', $result->title);
        self::assertSame('This is a test PR', $result->body);
        self::assertSame(42, $result->number);
        self::assertSame('main', $result->branch);
        self::assertSame('test_user', $result->authorUsername);
        self::assertSame('https://example.com/test_user', $result->authorProfileUrl);
        self::assertSame('https://example.com/pull/42', $result->url);
        self::assertInstanceOf(CarbonImmutable::class, $result->mergedAt);
        self::assertEquals('2024-12-31 12:31:30', $result->mergedAt); // loose comparison is fine
    }

    #[Test]
    public function nullBody_map_returnsPullRequestDataWithNullBody(): void
    {
        // Given
        $author = Author::factory()->make();
        $branch = Branch::factory()->make();

        $pullRequest = PullRequest::factory()->make([
            'title' => 'PR Without Body',
            'body' => null,
            'number' => 100,
            'url' => 'https://example.com/pull/100',
            'merged_at' => now(),
        ]);
        $pullRequest->setRelation('branch', $branch);
        $pullRequest->setRelation('author', $author);

        // When
        $result = $this->mapper->map($pullRequest);

        // Then
        self::assertInstanceOf(PullRequestData::class, $result);
        self::assertNull($result->body);
        self::assertSame('PR Without Body', $result->title);
    }
}
