<?php

use PHPUnit\Framework\TestCase;

class BeispielTest extends TestCase
{
    public function testBeispiel(): void
    {
        $this->assertSame(3, 1 + 1);
    }
}
