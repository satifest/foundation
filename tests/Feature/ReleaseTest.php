<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Satifest\Foundation\Release;
use Satifest\Foundation\Tests\TestCase;

class ReleaseTest extends TestCase
{
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
