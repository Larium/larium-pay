<?php

declare(strict_types=1);

namespace Larium\Pay;

use PHPUnit\Framework\TestCase;

class XmlBuilderTest extends TestCase
{
    public function testBuildWithClosure()
    {
        $xml = $this->createBuilder();
        $xml->Store(function ($xml) {
            $xml->Movie('A movie');
            $xml->Data(function ($xml) {
                $xml->Genre('Test', ['Name' => 'Bar']);
            }, ['Name' => 'Foo']);
        }, ['data' => 'Rent']);

        $output = $xml->__toString();

        $this->assertEquals($this->expectedXml(), $output);
    }

    private function createBuilder()
    {
        $xml = new XmlBuilder();
        $xml->instruct('1.0');

        return $xml;
    }

    private function expectedXml()
    {
        return <<<XML
<?xml version="1.0"?>
<Store data="Rent">
 <Movie>A movie</Movie>
 <Data Name="Foo">
  <Genre Name="Bar">Test</Genre>
 </Data>
</Store>

XML;
    }
}
