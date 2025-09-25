<?php

declare(strict_types=1);

namespace Tests\Integration\Models;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(User::class)]
final class UserModelTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function user_casts_returnsExpectedValues(): void
    {
        // Given
        $user = User::factory()->make();

        // When
        $emailVerifiedAt = $user->email_verified_at;

        // Then
        self::assertInstanceOf(CarbonImmutable::class, $emailVerifiedAt);
    }
}
