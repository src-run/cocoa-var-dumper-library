<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Tests\VarDumper\ReturnDumper;

use PHPUnit\Framework\TestCase;
use SR\Dumper\VarDumper\ReturnedCliDumper;

/**
 * @covers \SR\Dumper\VarDumper\ReturnedCliDumper
 */
class ReturnDumperTest extends TestCase
{
    /**
     * @dataProvider provideDumpData
     *
     * @param mixed|null $value
     */
    public function testDump(string $expected, $value = null): void
    {
        $this->assertStringMatchesFormat($expected, (new ReturnedCliDumper())->dump($value));
    }

    public static function provideDumpData(): \Iterator
    {
        $closedResource = fopen('php://memory', 'r+');
        fclose($closedResource);

        yield ['(14) "a string value"', 'a string value'];
        yield ['100', 100];
        yield ['100.0', 100.00];
        yield ['100.5', 100.50];
        yield ['100.333', 100.333];
        yield ['null', null];
        yield ['true', true];
        yield ['false', false];
        yield ['[ "a" => (5) "array", "with" => (6) "values" ]', ['a' => 'array', 'with' => 'values']];
        yield ['Closure() {#%d class: (%d) "%s" file: (%d) "%s" line: (8) "%d to %d" }', function () {}];
        yield ['class@anonymous {#%d}', new class() {
        }];
        yield [sprintf('%s {#%%d %%s}', __CLASS__), new static()];
        yield ['Closed resource @%d', $closedResource];
        yield ['stream resource {@%d %s}', fopen('php://memory', 'r+')];
        yield ['stream resource {@%d %s}', fopen(__FILE__, 'r+')];
        yield ['[]', []];
        yield ['[ "a" => (14) "a string value", "b" => 100, "c" => 33.333, "d" => null, "e" => true, "f" => false ]', ['a' => 'a string value', 'b' => 100, 'c' => 33.333, 'd' => null, 'e' => true, 'f' => false]];
        yield [sprintf('[ "anonymous-function" => Closure() {#%%d %%s}, "anonymous-object" => class@anonymous {#%%d}, "defined-object" => %s {#%%d %%s}, "open-stdio-resource" => stream resource {@%%d %%s}, "open-memory-resource" => stream resource {@%%d %%s}, "closed-resource" => Closed resource @%%d ]', __CLASS__), [
            'anonymous-function' => function () {},
            'anonymous-object' => new class() {
            },
            'defined-object' => new static(),
            'open-stdio-resource' => fopen(__FILE__, 'r+'),
            'open-memory-resource' => fopen('php://memory', 'r+'),
            'closed-resource' => $closedResource,
        ]];
    }
}
