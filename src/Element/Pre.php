<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Format;
use Unifor\Import\Html2Rest\Utility as ut;

class Pre extends Block
{
    public function render()
    {
        $process = ut\compose(
            ut\mapRenderUnlines(),
            ut\perLine(ut\prepend('  ')),
            ut\surround("\n::\n\n", "\n")
        );
        return $process($this->children);
    }
}
