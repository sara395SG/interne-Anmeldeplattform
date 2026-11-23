<?php

use PHPUnit\Framework\TestCase;

class BeispielTest extends TestCase
{
    public function testBeispiel(): void
    {
        $this->assertSame(2, 1 + 1);
    }
}
