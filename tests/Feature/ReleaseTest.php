<?php

namespace Satifest\Foundation\Tests\Feature;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Satifest\Foundation\Release;
use Satifest\Foundation\Tests\TestCase;

class ReleaseTest extends TestCase
{
    /** @test */
    public function it_can_cast_published_at_to_carbon()
    {
        $release = \factory(Release::class)->make([
            'published_at' => $now = Carbon::now(),
        ]);

        $publishedAt = $release->published_at;

        $this->assertInstanceOf(CarbonInterface::class, $publishedAt);
        $this->assertSame($now->toDateTimeString(), $publishedAt->toDateTimeString());
    }

    /** @test */
    public function it_can_get_name_attribute()
    {
        $release = \factory(Release::class)->make([
            'title' => 'Version 1',
            'version' => 'v1.0.0',
        ]);

        $this->assertSame('Version 1', $release->name);

        $release = \factory(Release::class)->make([
            'title' => null,
            'version' => 'v1.0.0',
        ]);

        $this->assertSame('v1.0.0', $release->name);
    }

    /** @test */
    public function it_belongs_to_repository_relation()
    {
        $release = \factory(Release::class)->make();

        $repository = $release->repository();

        $this->assertInstanceOf(BelongsTo::class, $repository);
        $this->assertSame('repository_id', $repository->getForeignKeyName());
        $this->assertSame('sf_releases.repository_id', $repository->getQualifiedForeignKeyName());
        $this->assertSame('id', $repository->getOwnerKeyName());
        $this->assertSame('sf_repositories.id', $repository->getQualifiedOwnerKeyName());
        $this->assertSame('repository', $repository->getRelationName());
    }
}
