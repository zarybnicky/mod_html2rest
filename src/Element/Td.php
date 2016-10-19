<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Td extends Block
{
    public function render()
    {
        $process = ut\map(ut\render(), 'trim');
        return $process($this->children);
    }
}
