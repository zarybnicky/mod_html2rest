<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Blockquote extends Block
{
    public function render()
    {
        $process = ut\compose(
            ut\map(
                ut\render(),
                ut\perLine(ut\prepend('  '))
            ),
            ut\unlines(),
            ut\prepend("\n"),
            (!($this->parent instanceof Blockquote)) ? ut\append("\n") : ut\id()
        );
        return $process($this->children);
    }
}
