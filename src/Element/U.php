<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Format;

class U extends Inline
{
    public function __construct($node, $parent = null)
    {
        parent::__construct($node, $parent);
        $this->pushFormat(Format::UNDERLINE);
    }
}
