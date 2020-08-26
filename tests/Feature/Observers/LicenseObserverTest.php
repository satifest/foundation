<?php

namespace Satifest\Foundation\Tests\Feature\Observers;

use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\LicenseChanged;
use Satifest\Foundation\Events\LicenseCreated;
use Satifest\Foundation\License;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

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

        $user = \factory(User::class)->create();

        \factory(License::class)->create([
            'licensee_id' => $user->getKey(),
            'licensee_type' => $user->getMorphClass(),
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

        $user = \factory(User::class)->create();

        $license = \factory(License::class)->create([
            'licensee_id' => $user->getKey(),
            'licensee_type' => $user->getMorphClass(),
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

        $user = \factory(User::class)->create();

        $license = \factory(License::class)->create([
            'licensee_id' => $user->getKey(),
            'licensee_type' => $user->getMorphClass(),
        ]);

        $license->delete();

        Event::assertDispatched(function (LicenseChanged $event) use ($license) {
            return $event->license->id === $license->id;
        });
    }
}
