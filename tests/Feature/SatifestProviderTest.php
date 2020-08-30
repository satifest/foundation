<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Support\Enumerable;
use Satifest\Foundation\Tests\TestCase;

class SatifestProviderTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set([
            'satifest.github.token' => 'github-token',
            'satifest.github.webhook-secret' => 'github-webhook-secret',
            'satifest.gitlab.token' => 'gitlab-token',
            'satifest.gitlab.webhook-secret' => 'gitlab-webhook-secret',
        ]);
    }

    /** @test */
    public function it_has_the_proper_providers()
    {
        $providers = $this->app->make('satifest.provider');

        $this->assertInstanceOf(Enumerable::class, $providers);

        $this->assertSame([
            'name' => 'GitHub',
            'token' => 'github-token',
            'webhook-secret' => 'github-webhook-secret',
        ], $providers->get('github'));

        $this->assertSame([
            'name' => 'GitLab',
            'token' => 'gitlab-token',
            'webhook-secret' => 'gitlab-webhook-secret',
        ], $providers->get('gitlab'));
    }
}
