<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\Author;
use App\Models\PullRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Author::class)]
final class AuthorModelTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function authorHasManyPullRequests_pullRequests_returnsCorrectPullRequests(): void
    {
        // Given
        $pullRequests = PullRequest::factory()->count(4)->make();
        $author = Author::factory()->make();
        $author->setRelation('pullRequests', $pullRequests);

        // When
        $pullRequests = $author->pullRequests;

        // Then
        self::assertCount(4, $pullRequests);
        foreach ($pullRequests as $pullRequest) {
            self::assertInstanceOf(PullRequest::class, $pullRequest);
        }
    }

    #[Test]
    public function authorWithoutPullRequests_pullRequests_returnsNoPullRequests(): void
    {
        // Given
        $author = Author::factory()->make();

        // When
        $pullRequests = $author->pullRequests;

        // Then
        self::assertCount(0, $pullRequests);
    }
}
