<?php

namespace Satifest\Foundation\Tests\Feature;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;
use Satifest\Foundation\Licensing;
use Satifest\Foundation\Release;
use Satifest\Foundation\Testing\Factories\ReleaseFactory;
use Satifest\Foundation\Testing\Factories\RepositoryFactory;
use Satifest\Foundation\Testing\Factories\UserFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Release feature tests
 */
class ReleaseTest extends TestCase
{
    /** @test */
    public function it_can_use_accessible_by_scope()
    {
        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $user = UserFactory::new()->create();

        $user->createLicense(Licensing::makeSponsorware('github', __METHOD__, Money::USD(1000)), '*');

        $query = Release::query()->accessibleBy($user);

        $this->assertSame(
            'select * from "sf_releases" where exists (select * from "sf_repositories" where "sf_releases"."repository_id" = "sf_repositories"."id" and "sf_repositories"."id" in (?))',
            $query->toSql()
        );

        $this->assertSame(
            [$repository->getKey()], $query->getBindings()
        );
    }

    /** @test */
    public function it_can_use_by_name_scope()
    {
        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.0.0.0',
            'version' => 'v1.0.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.1.0.0',
            'version' => 'v1.1.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => 2,
            'semver' => '0.1.0.0',
            'version' => 'v0.1.0',
        ]);

        $query = Release::byName('satifest/demo-test-package');

        $this->assertSame(
            'select * from "sf_releases" where exists (select * from "sf_repositories" where "sf_releases"."repository_id" = "sf_repositories"."id" and "sf_repositories"."name" = ?)',
            $query->toSql()
        );

        $releases = $query->get();

        $this->assertSame([
            'v1.0.0',
            'v1.1.0',
        ], $releases->pluck('version')->all());
    }

    /** @test */
    public function it_can_use_by_name_scope_with_name_as_array()
    {
        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $query = Release::byName(['satifest', 'demo-test-package']);

        $this->assertSame(
            'select * from "sf_releases" where exists (select * from "sf_repositories" where "sf_releases"."repository_id" = "sf_repositories"."id" and "sf_repositories"."name" = ?)',
            $query->toSql()
        );

        $this->assertSame(
            ['satifest/demo-test-package'], $query->getBindings()
        );
    }

    /** @test */
    public function it_can_use_by_package_scope()
    {
        $repository = RepositoryFactory::new()->create([
            'package' => 'satifest/demo-test-package',
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.0.0.0',
            'version' => 'v1.0.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.1.0.0',
            'version' => 'v1.1.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => 2,
            'semver' => '0.1.0.0',
            'version' => 'v0.1.0',
        ]);

        $query = Release::byPackage('satifest/demo-test-package');

        $this->assertSame(
            'select * from "sf_releases" where exists (select * from "sf_repositories" where "sf_releases"."repository_id" = "sf_repositories"."id" and "sf_repositories"."package" = ?)',
            $query->toSql()
        );

        $releases = $query->get();

        $this->assertSame([
            'v1.0.0',
            'v1.1.0',
        ], $releases->pluck('version')->all());
    }

    /** @test */
    public function it_can_use_by_package_scope_with_name_as_array()
    {
        $repository = RepositoryFactory::new()->create([
            'package' => 'satifest/demo-test-package',
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $query = Release::byPackage(['satifest', 'demo-test-package']);

        $this->assertSame(
            'select * from "sf_releases" where exists (select * from "sf_repositories" where "sf_releases"."repository_id" = "sf_repositories"."id" and "sf_repositories"."package" = ?)',
            $query->toSql()
        );

        $this->assertSame(
            ['satifest/demo-test-package'], $query->getBindings()
        );
    }

    /** @test */
    public function it_can_use_stable_release_scope()
    {
        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.0.0.0',
            'version' => 'v1.0.0',
            'type' => 'stable',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '1.1.0.0',
            'version' => 'v1.1.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => 2,
            'semver' => '0.1.0.0',
            'version' => 'v0.1.0',
        ]);

        ReleaseFactory::new()->create([
            'repository_id' => $repository->id,
            'semver' => '9999999-dev',
            'version' => 'dev-master',
            'type' => Release::NIGHTLY,
        ]);

        $query = Release::stable();

        $this->assertSame('select * from "sf_releases" where "type" in (?)', $query->toSql());
        $this->assertSame(['stable'], $query->getBindings());

        $releases = $query->get();

        $this->assertSame([
            'v1.0.0',
            'v1.1.0',
            'v0.1.0',
        ], $releases->pluck('version')->all());
    }

    /** @test */
    public function it_can_cast_published_at_to_carbon()
    {
        $release = ReleaseFactory::new()->make([
            'published_at' => $now = Carbon::now(),
        ]);

        $publishedAt = $release->published_at;

        $this->assertInstanceOf(CarbonInterface::class, $publishedAt);
        $this->assertSame($now->toDateTimeString(), $publishedAt->toDateTimeString());
    }

    /** @test */
    public function it_can_get_name_attribute()
    {
        $release = ReleaseFactory::new()->make([
            'title' => 'Version 1',
            'version' => 'v1.0.0',
        ]);

        $this->assertSame('Version 1', $release->name);

        $release = ReleaseFactory::new()->make([
            'title' => null,
            'version' => 'v1.0.0',
        ]);

        $this->assertSame('v1.0.0', $release->name);
    }

    /** @test */
    public function it_belongs_to_repository_relation()
    {
        $release = ReleaseFactory::new()->make();

        $repository = $release->repository();

        $this->assertInstanceOf(BelongsTo::class, $repository);
        $this->assertSame('repository_id', $repository->getForeignKeyName());
        $this->assertSame('sf_releases.repository_id', $repository->getQualifiedForeignKeyName());
        $this->assertSame('id', $repository->getOwnerKeyName());
        $this->assertSame('sf_repositories.id', $repository->getQualifiedOwnerKeyName());
        $this->assertSame('repository', $repository->getRelationName());
    }

    /** @test */
    public function it_can_verify_stable_version()
    {
        $release = ReleaseFactory::new()->make([
            'type' => Release::STABLE,
        ]);

        $this->assertTrue($release->stableVersion());
        $this->assertFalse($release->notStableVersion());
    }

    /** @test */
    public function it_can_verify_unstable_version()
    {
        $release = ReleaseFactory::new()->make([
            'type' => Release::NIGHTLY,
        ]);

        $this->assertFalse($release->stableVersion());
        $this->assertTrue($release->notStableVersion());
    }
}
