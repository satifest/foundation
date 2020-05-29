<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Satifest\Foundation\Repository;
use Satifest\Foundation\Tests\TestCase;

class RepositoryTest extends TestCase
{
    /** @test */
    public function it_can_cast_name_from_url()
    {
        $repository = \factory(Repository::class)->make();

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
        $repository = \factory(Repository::class)->make();

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
        $repository = \factory(Repository::class)->make();

        $releases = $repository->releases();

        $this->assertInstanceOf(HasMany::class, $releases);
        $this->assertNull($releases->getParentKey());
        $this->assertSame('sf_repositories.id', $releases->getQualifiedParentKeyName());
        $this->assertSame('repository_id', $releases->getForeignKeyName());
        $this->assertSame('sf_releases.repository_id', $releases->getQualifiedForeignKeyName());
        $this->assertSame('id', $releases->getLocalKeyName());
    }
}
