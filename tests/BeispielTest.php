<?php
//erste beiden Zeilen immer so schreiben
use PHPUnit\Framework\TestCase;

// Dateiname und Klassenname (hier BeispielTest) müssen gleich sein und auf "Test" enden, immer extends TestCase
class BeispielTest extends TestCase
{
    // Nur die public functions, die mit "test" beginnen, werden bei pull-request und push automatisch ausgeführt
    public function testBeispiel(): void
    {
        // Mehrere asserts sind erlaubt, aber wenn einer fails werden die nächsten (in der selben Funktion) nicht durchgeführt 
        $this->assertSame(2, 1 + 1);
    }
}
