<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Branch;
use App\Models\PullRequest;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Repository::class)]
final class RepositoryModelTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function repository_slug_returnsTheOwnerAndRepoSlug(): void
    {
        // Given
        $repository = Repository::factory()->make([
            'owner' => 'laravel',
            'repo' => 'framework',
        ]);

        // When
        $slug = $repository->slug;

        // Then
        self::assertSame('laravel/framework', $slug);
    }

    #[Test]
    public function repositoryWithBranches_branches_returnsCorrectBranches(): void
    {
        // Given
        $repository = Repository::factory()->make();
        $branches = Branch::factory()->count(3)->make();
        $repository->setRelation('branches', $branches);

        // When
        $branches = $repository->branches;

        // Then
        self::assertCount(3, $branches);
        foreach ($branches as $branch) {
            self::assertInstanceOf(Branch::class, $branch);
        }
    }

    #[Test]
    public function repositoryWithPullRequests_pullRequests_returnsCorrectPullRequests(): void
    {
        // Given
        $repository = Repository::factory()->make();
        $pullRequests = PullRequest::factory()->count(4)->make();
        $repository->setRelation('pullRequests', $pullRequests);

        // When
        $pullRequests = $repository->pullRequests;

        // Then
        self::assertCount(4, $pullRequests);
        foreach ($pullRequests as $pullRequest) {
            self::assertInstanceOf(PullRequest::class, $pullRequest);
        }
    }
}
