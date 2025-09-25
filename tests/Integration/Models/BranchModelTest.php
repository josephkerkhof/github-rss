<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Branch;
use App\Models\PullRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Branch::class)]
final class BranchModelTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function branchHasManyPullRequests_pullRequests_returnsCorrectPullRequests(): void
    {
        // Given
        $pullRequests = PullRequest::factory()->count(4)->make();
        $branch = Branch::factory()->make();
        $branch->setRelation('pullRequests', $pullRequests);

        // When
        $pullRequests = $branch->pullRequests;

        // Then
        self::assertCount(4, $pullRequests);
        foreach ($pullRequests as $pullRequest) {
            self::assertInstanceOf(PullRequest::class, $pullRequest);
        }
    }

    #[Test]
    public function branchWithoutPullRequests_pullRequests_returnsNoPullRequests(): void
    {
        // Given
        $branch = Branch::factory()->make();

        // When
        $pullRequests = $branch->pullRequests;

        // Then
        self::assertCount(0, $pullRequests);
    }
}
