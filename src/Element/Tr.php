<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Tr extends Block
{
    public function render()
    {
        $process = ut\mapRender();
        return $process($this->children);
    }
}
