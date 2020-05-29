<?php

namespace Satifest\Foundation\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use function Satifest\Foundation\column_name;
use Satifest\Foundation\Tests\User;

/**
 * @testdox Satifest\Foundation\column_name() tests
 */
class ColumnNameTest extends TestCase
{
    /** @test */
    public function it_can_translate_column_name()
    {
        $column = column_name(User::class, 'email');

        $this->assertSame('users.email', $column);
    }

    /** @test */
    public function it_can_translate_column_name_when_given_an_instance_of_eloquent()
    {
        $column = column_name(new User(), 'email');

        $this->assertSame('users.email', $column);
    }

    /** @test */
    public function it_cant_translate_column_name_when_not_given_an_instance_of_eloquent()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Given $model is not an instance of [Illuminate\Database\Eloquent\Model].');

        $column = column_name(new class() {
        }, 'email');
    }
}
