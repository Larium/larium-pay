<?php

declare(strict_types=1);

namespace Larium\Pay;

use PHPUnit\Framework\TestCase;

class ParamsBagTest extends TestCase
{
    public function testShouldAssignValue(): void
    {
        $p = new ParamsBag();

        $p->test = 1;

        $this->assertEquals(1, $p->test);
        $this->assertNull($p->notExists);
        $this->assertEquals(2, $p->get('notExists', 2));

    }

    public function testShouldAssignArrayValues(): void
    {
        $p = new ParamsBag([
            'test' => 1,
            'a' => 'b'
        ]);

        $this->assertEquals(1, $p->test);
        $this->assertEquals('b', $p->a);

        $this->assertEquals(1, $p->current());

    }
}
