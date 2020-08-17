<?php

namespace Satifest\Foundation\Tests\Feature\Observers;

use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\RepoChanged;
use Satifest\Foundation\Events\RepoCreated;
use Satifest\Foundation\Events\RepositoryChanged;
use Satifest\Foundation\Events\RepositoryCreated;
use Satifest\Foundation\Repository;
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
            RepoCreated::class,
            RepositoryCreated::class,
        ]);

        $repository = \factory(Repository::class)->create([
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $this->assertDatabaseHas('sf_plans', [
            'repository_id' => $repository->getKey(),
            'name' => 'Plan for satifest/demo-test-package',
            'constraint' => '*',
        ]);

        Event::assertDispatched(RepoCreated::class);
        Event::assertDispatched(RepositoryCreated::class);
    }

    /** @test */
    public function it_triggers_repository_changed_on_update()
    {
        Event::fake([
            RepoCreated::class,
            RepoChanged::class,
            RepositoryCreated::class,
            RepositoryChanged::class,
        ]);

        $repository = \factory(Repository::class)->create([
            'url' => 'https://github.com/satifest/satifest',
        ]);

        $repository->url = 'https://github.com/satifest/demo-test-package';
        $repository->save();

        Event::assertDispatched(function (RepoChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });

        Event::assertDispatched(function (RepositoryChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });
    }

    /** @test */
    public function it_triggers_repository_changed_on_delete()
    {
        Event::fake([
            RepoCreated::class,
            RepoChanged::class,
            RepositoryCreated::class,
            RepositoryChanged::class,
        ]);

        $repository = \factory(Repository::class)->create([
            'url' => 'https://github.com/satifest/satifest',
        ]);

        $repository->delete();

        Event::assertDispatched(function (RepoChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });

        Event::assertDispatched(function (RepositoryChanged $event) use ($repository) {
            return $event->repository->id === $repository->id;
        });
    }
}
