<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Money\Money;
use Satifest\Foundation\Licensing;
use Satifest\Foundation\Repository;
use Satifest\Foundation\Testing\Factories\RepositoryFactory;
use Satifest\Foundation\Testing\Factories\UserFactory;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Value\HookUrl;
use Spatie\TestTime\TestTime;

/**
 * @testdox Satifest\Foundation\Repository feature tests
 */
class RepositoryTest extends TestCase
{
    /** @test */
    public function it_can_use_accessible_by_scope()
    {
        TestTime::freeze();

        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $user = UserFactory::new()->create();

        $user->createLicense(Licensing::makeSponsorware('github', __METHOD__, Money::USD(1000)), '*');

        $query = Repository::query()->accessibleBy($user);

        $this->assertSame(
            'select * from "sf_repositories" where exists (select * from "sf_plans" where "sf_repositories"."id" = "sf_plans"."repository_id" and exists (select * from "sf_licenses" inner join "sf_license_plan" on "sf_licenses"."id" = "sf_license_plan"."license_id" where ("sf_plans"."id" = "sf_license_plan"."plan_id" and ("licensable_id" = ? and "licensable_type" = ?) or (exists (select * from "sf_teams" where "sf_licenses"."id" = "sf_teams"."license_id" and "sf_teams"."user_id" = ?))) and ("ends_at" is null or "ends_at" > ?)) and "sf_plans"."deleted_at" is null)',
            $query->toSql()
        );

        $bindings = $query->getBindings();

        $this->assertCount(4, $bindings);
        $this->assertSame($user->getKey(), $bindings[0]);
        $this->assertSame($user->getMorphClass(), $bindings[1]);
        $this->assertSame($user->getKey(), $bindings[2]);
        $this->assertSame(now()->toDatetimeString(), $bindings[3]->toDatetimeString());
    }

    /** @test */
    public function it_can_use_by_url_scope()
    {
        $query = Repository::query()->byUrl('https://github.com/satifest/test-demo-package');

        $this->assertSame(
            'select * from "sf_repositories" where "url" = ?',
            $query->toSql()
        );

        $this->assertSame(
            ['https://github.com/satifest/test-demo-package'], $query->getBindings()
        );
    }

    /** @test */
    public function it_can_use_by_url_scope_but_will_not_return_any_result_if_given_null()
    {
        $query = Repository::query()->byUrl(null);

        $this->assertSame(
            'select * from "sf_repositories" where "id" < ?',
            $query->toSql()
        );

        $this->assertSame(
            [1], $query->getBindings()
        );
    }

    /** @test */
    public function it_can_get_composer_name_attribute_from_package()
    {
        $repository = RepositoryFactory::new()->make([
            'package' => 'satifest/demo-test',
            'name' => 'satifest/demo-test-package',
        ]);

        $this->assertSame('satifest/demo-test', $repository->composer_name);
    }

    /** @test */
    public function it_can_get_composer_name_attribute_from_name()
    {
        $repository = RepositoryFactory::new()->make([
            'name' => 'satifest/demo-test-package',
        ]);

        $this->assertSame('satifest/demo-test-package', $repository->composer_name);
    }

    /** @test */
    public function it_can_get_hook_url_for_github()
    {
        $repository = RepositoryFactory::new()->make([
            'url' => 'https://github.com/satifest/demo-test-package',
            'provider' => 'github',
        ]);

        $hookUrl = $repository->hook_url;

        $this->assertInstanceOf(HookUrl::class, $hookUrl);
        $this->assertSame('https://github.com/satifest/demo-test-package/settings/hooks/new', (string) $hookUrl);
    }

    /** @test */
    public function it_can_get_hook_url_for_gitlab()
    {
        $repository = RepositoryFactory::new()->make([
            'url' => 'https://gitlab.com/satifest/demo-test-package',
            'provider' => 'gitlab',
        ]);

        $hookUrl = $repository->hook_url;

        $this->assertInstanceOf(HookUrl::class, $hookUrl);
        $this->assertSame('https://gitlab.com/satifest/demo-test-package/hooks', (string) $hookUrl);
    }

    /** @test */
    public function it_can_cast_name_from_url()
    {
        $repository = RepositoryFactory::new()->make();

        $repository->url = 'https://github.com/satifest/demo-test-package';

        $this->assertSame('satifest/demo-test-package', $repository->name);
        $this->assertSame('https://github.com/satifest/demo-test-package', $repository->url);

        $repository->url = 'https://github.com/satifest/demo-test-package.git';

        $this->assertSame('satifest/demo-test-package', $repository->name);
        $this->assertSame('https://github.com/satifest/demo-test-package.git', $repository->url);
    }

    /** @test */
    public function it_has_many_plans_relation()
    {
        $repository = RepositoryFactory::new()->make();

        $plans = $repository->plans();

        $this->assertInstanceOf(HasMany::class, $plans);
        $this->assertNull($plans->getParentKey());
        $this->assertSame('sf_repositories.id', $plans->getQualifiedParentKeyName());
        $this->assertSame('repository_id', $plans->getForeignKeyName());
        $this->assertSame('sf_plans.repository_id', $plans->getQualifiedForeignKeyName());
        $this->assertSame('id', $plans->getLocalKeyName());
    }

    /** @test */
    public function it_has_many_releases_relation()
    {
        $repository = RepositoryFactory::new()->make();

        $releases = $repository->releases();

        $this->assertInstanceOf(HasMany::class, $releases);
        $this->assertNull($releases->getParentKey());
        $this->assertSame('sf_repositories.id', $releases->getQualifiedParentKeyName());
        $this->assertSame('repository_id', $releases->getForeignKeyName());
        $this->assertSame('sf_releases.repository_id', $releases->getQualifiedForeignKeyName());
        $this->assertSame('id', $releases->getLocalKeyName());
    }
}
