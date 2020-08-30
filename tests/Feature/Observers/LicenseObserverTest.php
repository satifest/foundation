<?php

namespace Satifest\Foundation\Tests\Feature\Observers;

use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\LicenseChanged;
use Satifest\Foundation\Events\LicenseCreated;
use Satifest\Foundation\Testing\Factories\LicenseFactory;
use Satifest\Foundation\Testing\Factories\UserFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Observers\LicenseObserver feature tests
 */
class LicenseObserverTest extends TestCase
{
    /** @test */
    public function it_triggers_license_created_on_update()
    {
        Event::fake([
            LicenseCreated::class,
        ]);

        $user = UserFactory::new()->create();

        LicenseFactory::new()->create([
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        Event::assertDispatched(LicenseCreated::class);
    }

    /** @test */
    public function it_triggers_license_changed_on_update()
    {
        Event::fake([
            LicenseCreated::class,
            LicenseChanged::class,
        ]);

        $user = UserFactory::new()->create();

        $license = LicenseFactory::new()->create([
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        $license->allocation = 10;
        $license->save();

        Event::assertDispatched(function (LicenseChanged $event) use ($license) {
            return $event->license->id === $license->id;
        });
    }

    /** @test */
    public function it_triggers_license_changed_on_delete()
    {
        Event::fake([
            LicenseCreated::class,
            LicenseChanged::class,
        ]);

        $user = UserFactory::new()->create();

        $license = LicenseFactory::new()->create([
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        $license->delete();

        Event::assertDispatched(function (LicenseChanged $event) use ($license) {
            return $event->license->id === $license->id;
        });
    }
}
