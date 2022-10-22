<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Tests\Normalizer\Manager;

use PHPUnit\Framework\TestCase;
use SR\Dumper\Normalizer\Manager\NullNormalizer;
use SR\Dumper\Transformer\StringTransformer;

/**
 * @covers \SR\Dumper\Normalizer\Manager\AbstractNormalizer
 * @covers \SR\Dumper\Normalizer\Manager\NullNormalizer
 * @covers \SR\Dumper\Normalizer\Runner\AbstractRunner
 * @covers \SR\Dumper\Normalizer\Runner\NullType\NullRunner
 */
class NullNormalizerTest extends TestCase
{
    /**
     * @dataProvider provideNormalizerData
     *
     * @param string     $expected
     * @param mixed|null $value
     */
    public function testNormalizer(string $expected, $value = null): void
    {
        $this->assertStringMatchesFormat($expected, (new StringTransformer(new NullNormalizer()))($value));
    }

    /**
     * @return \Iterator
     */
    public static function provideNormalizerData(): \Iterator
    {
        $closedResource = fopen('php://memory', 'r+');
        fclose($closedResource);

        yield ['a string value', 'a string value'];
        yield ['100', 100];
        yield ['100.0', 100.00];
        yield ['100.5', 100.50];
        yield ['100.333', 100.333];
        yield ['null', null];
        yield ['true', true];
        yield ['false', false];
        yield ['Closure() {#%d class: (%d) "%s" file: (%d) "%s" line: (8) "%d to %d" }', function () {}];
        yield ['class@anonymous {#%d}', new class() {
        }];
        yield [sprintf('%s {#%%d %%s}', __CLASS__), new static()];
        yield ['Closed resource @%d', $closedResource];
        yield ['stream resource {@%d %s}', fopen('php://memory', 'r+')];
        yield ['stream resource {@%d %s}', fopen(__FILE__, 'r+')];
        yield ['[ "a" => (string) "a string value", "b" => (int) "100", "c" => (float) "33.333", "d" => (null) "null", "e" => (bool) "true", "f" => (bool) "false" ] (6)', ['a' => 'a string value', 'b' => 100, 'c' => 33.333, 'd' => null, 'e' => true, 'f' => false]];
        yield [sprintf('[ "anonymous-function" => (object) "Closure() {#%%d %%s}", "anonymous-object" => (object) "class@anonymous {#%%d}", "castable-object" => (object) "spl-object-hash:%%s", "defined-object" => (object) "%s {#%%d %%s}", "open-stdio-resource" => (resource) "stream resource {@%%d %%s}", "open-memory-resource" => (resource) "stream resource {@%%d %%s}", "closed-resource" => (resource) "Closed resource @%%d" ] (7)', __CLASS__), [
            'anonymous-function' => function () {},
            'anonymous-object' => new class() {
            },
            'castable-object' => new class() {
                public function __toString()
                {
                    return sprintf('spl-object-hash:%s', spl_object_hash($this));
                }
            },
            'defined-object' => new static(),
            'open-stdio-resource' => fopen(__FILE__, 'r+'),
            'open-memory-resource' => fopen('php://memory', 'r+'),
            'closed-resource' => $closedResource,
        ]];
    }
}
