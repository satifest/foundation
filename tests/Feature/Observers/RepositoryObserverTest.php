<?php

namespace Satifest\Foundation\Tests\Feature\Observers;

use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\RepositoryChanged;
use Satifest\Foundation\Events\RepositoryCreated;
use Satifest\Foundation\Repository;
use Satifest\Foundation\Testing\Factories\RepositoryFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Observers\RepositoryObserver feature tests
 */
class RepositoryObserverTest extends TestCase
{
    /** @test */
    public function it_create_default_plan_on_create()
    {
        Event::fake([
            RepositoryCreated::class,
        ]);

        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $this->assertDatabaseHas('sf_plans', [
            'repository_id' => $repository->getKey(),
            'name' => 'Plan for satifest/demo-test-package',
            'constraint' => '*',
        ]);

        Event::assertDispatched(RepositoryCreated::class);
    }

    /** @test */
    public function it_triggers_repository_changed_on_update()
    {
        Event::fake([
            RepositoryCreated::class,
            RepositoryChanged::class,
        ]);

        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/satifest',
        ]);

        $repository->url = 'https://github.com/satifest/demo-test-package';
        $repository->save();

        Event::assertDispatched(function (RepositoryChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });
    }

    /** @test */
    public function it_triggers_repository_changed_on_delete()
    {
        Event::fake([
            RepositoryCreated::class,
            RepositoryChanged::class,
        ]);

        $repository = RepositoryFactory::new()->create([
            'url' => 'https://github.com/satifest/satifest',
        ]);

        $repository->delete();

        Event::assertDispatched(function (RepositoryChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });
    }
}
