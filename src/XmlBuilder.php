<?php

declare(strict_types=1);

namespace Larium\Pay;

use XMLWriter;

/**
 * XmlBuilder provides a fluent way to create xml strings.
 *
 * @author Andreas Kollaros <andreas@larium.net>
 */
class XmlBuilder
{
    protected $writer;

    public function __construct()
    {
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
    }

    public function instruct($version, $encoding = null, $indent = true)
    {
        $this->writer->startDocument($version, $encoding);
        if ($indent) {
            $this->writer->setIndent(true);
        }
    }

    public function docType($qualifiedName, $publicId = null, $systemId = null)
    {
        $this->writer->startDTD($qualifiedName, $publicId, $systemId);
        $this->writer->endDTD();
    }

    public function build()
    {
        $args = func_get_args();

        $args = reset($args);
        $name = array_shift($args);
        $block_or_string = array_shift($args);
        $attributes = array_shift($args);

        $this->createElement($name, $block_or_string, $attributes);

        return $this;
    }

    public function __call($name, $args)
    {
        $args = array_merge([$name], $args);

        return $this->build($args);
    }

    public function __toString()
    {
        $this->writer->endDocument();

        return $this->writer->outputMemory();
    }

    private function createElement($name, $body, array $attributes = null)
    {
        $this->writer->startElement($name);

        $this->createAttributes($attributes);

        if (is_callable($body)) {
            $body($this);
        } else {
            $this->writer->text($body);
        }

        $this->writer->endElement();
    }

    private function createAttributes(array $attributes = null)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->writer->startAttribute($key);
                $this->writer->text($value);
                $this->writer->endAttribute();
            }
        }
    }
}
