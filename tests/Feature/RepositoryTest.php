<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\RepoCreated;
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
    public function it_create_default_plan_on_create()
    {
        Event::fake([
            RepoCreated::class,
        ]);

        $repository = \factory(Repository::class)->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $this->assertDatabaseHas('sf_plans', [
            'repository_id' => $repository->getKey(),
            'name' => 'Basic Plan for satifest/demo-test-package',
            'constraint' => '*',
        ]);
    }
}
