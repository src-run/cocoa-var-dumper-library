<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Tests\Normalizer\Runner;

use PHPUnit\Framework\TestCase;
use SR\Dumper\Normalizer\Runner\NullType\NullRunner;
use SR\Dumper\Normalizer\Runner\StringType\ClosureStringRunner;
use SR\Dumper\Normalizer\Runner\StringType\ObjectStringRunner;
use SR\Dumper\Normalizer\Runner\StringType\ResourceStringRunner;

/**
 * @covers \SR\Dumper\Normalizer\Runner\AbstractRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ClosureStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ObjectStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\ResourceStringRunner
 * @covers \SR\Dumper\Normalizer\Runner\StringType\StringRunnerTrait
 */
class RunnerTest extends TestCase
{
    /**
     * @dataProvider provideRunnerData
     */
    public function testNormalizer(string $class): void
    {
        $this->assertSame($class, (new $class())->type());
    }

    public static function provideRunnerData(): \Iterator
    {
        yield [ClosureStringRunner::class];
        yield [ObjectStringRunner::class];
        yield [ResourceStringRunner::class];
        yield [NullRunner::class];
    }
}
