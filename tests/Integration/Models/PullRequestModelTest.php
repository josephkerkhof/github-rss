<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;
use App\Models\Repository;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(PullRequest::class)]
final class PullRequestModelTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function pullRequest_casts_getExpectedType(): void
    {
        // Given
        $pullRequest = PullRequest::factory()->make();

        // When
        $mergedAt = $pullRequest->merged_at;

        // Then
        self::assertInstanceOf(CarbonImmutable::class, $mergedAt);
    }

    #[Test]
    public function pullRequestWithRepository_repository_returnsRepository(): void
    {
        // Given
        $pullRequest = PullRequest::factory()->make();
        $repository = Repository::factory()->make();
        $pullRequest->repository()->associate($repository);

        // When
        $repositoryRelation = $pullRequest->repository;

        // Then
        self::assertInstanceOf(Repository::class, $repositoryRelation);
        self::assertTrue($repositoryRelation->is($repository));
    }

    #[Test]
    public function pullRequestWithBranch_branch_returnsBranch(): void
    {
        // Given
        $pullRequest = PullRequest::factory()->make();
        $branch = Branch::factory()->make();
        $pullRequest->branch()->associate($branch);

        // When
        $branchRelation = $pullRequest->branch;

        // Then
        self::assertInstanceOf(Branch::class, $branchRelation);
        self::assertTrue($branchRelation->is($branch));
    }

    #[Test]
    public function pullRequestWithAuthor_branch_returnsAuthor(): void
    {
        // Given
        $pullRequest = PullRequest::factory()->make();
        $author = Author::factory()->make();
        $pullRequest->author()->associate($author);

        // When
        $authorRelation = $pullRequest->author;

        // Then
        self::assertInstanceOf(Author::class, $authorRelation);
        self::assertTrue($authorRelation->is($author));
    }
}
