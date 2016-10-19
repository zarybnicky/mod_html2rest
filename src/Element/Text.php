<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Format;

class Text extends Inline
{
    protected $declaration;

    public function render()
    {
        list($text, $declaration) = Format::decorate(
            preg_replace(
                array('/\s+/u', '/\s([?.!])/u'),
                array(' ','$1'),
                $this->node->wholeText
            ),
            $this->format
        );
        $this->declaration = $declaration;
        return $text;
    }

    public function cleanEmptyNodes()
    {
    }

    public function getDeclarations()
    {
        return $this->declaration ? array($this->declaration) : array();
    }

    public function isEmpty()
    {
        return !trim($this->render());
    }
}
