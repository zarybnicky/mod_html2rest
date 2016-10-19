<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Inline extends Base
{
    public function render()
    {
        $process = ut\mapRenderConcat();
        return $process($this->children);
    }

    public function append($element)
    {
        $this->children[] = $element;
    }

    public function cleanEmptyNodes()
    {
    }

    public function isEmpty()
    {
        return !$this->render() && !$this->getReferences();
    }
}