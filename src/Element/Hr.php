<?php
namespace Unifor\Import\Html2Rest\Element;

class Hr extends Block
{
    public function render()
    {
        return "\n" . str_repeat('-', 8) . "\n";
    }
}
