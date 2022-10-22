<?php

/*
 * This file is part of the `src-run/cocoa-var-dumper-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Dumper\Tests\Transformer\StringTransformer;

use PHPUnit\Framework\TestCase;
use SR\Dumper\Transformer\StringTransformer;

/**
 * @covers \SR\Dumper\Transformer\StringTransformer
 */
class StringTransformerTest extends TestCase
{
    /**
     * @dataProvider provideTransformData
     *
     * @param mixed|null $value
     */
    public function testTransform(string $expected, $value = null): void
    {
        $this->assertStringMatchesFormat($expected, (new StringTransformer())($value));
    }

    public static function provideTransformData(): \Iterator
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
        yield ['closure {#%d @ %s:%d-%d}', function () {}];
        yield ['class@anonymous {#%d}', new class() {
        }];
        yield [sprintf('%s {#%%d}', __CLASS__), new static()];
        yield ['closed resource {#%d}', $closedResource];
        yield ['stream resource {#%d @ php://memory (memory)}', fopen('php://memory', 'r+')];
        yield ['stream resource {#%d @ %s (stdio)}', fopen(__FILE__, 'r+')];
        yield ['[ ] (0)', []];
        yield ['[ "a" => (string) "a string value", "b" => (int) "100", "c" => (float) "33.333", "d" => (null) "null", "e" => (bool) "true", "f" => (bool) "false" ] (6)', ['a' => 'a string value', 'b' => 100, 'c' => 33.333, 'd' => null, 'e' => true, 'f' => false]];
        yield [sprintf('[ "anonymous-function" => (object) "closure {#%%d @ %%s:%%d-%%d}", "anonymous-object" => (object) "class@anonymous {#%%d}", "castable-object" => (object) "spl-object-hash:%%s", "defined-object" => (object) "%s {#%%d}", "open-stdio-resource" => (resource) "stream resource {#%%d @ %%s.php (stdio)}", "open-memory-resource" => (resource) "stream resource {#%%d @ php://memory (memory)}", "closed-resource" => (resource) "closed resource {#%%d}" ] (7)', __CLASS__), [
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
