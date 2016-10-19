<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Dl extends Block
{
    public function render()
    {
        if (!$this->children)
        {
            return '';
        }

        $process = ut\compose(
            ut\map(
                ut\cond(
                    function ($x) {
                        return $x instanceof Dd;
                    },
                    ut\compose(
                        ut\render(),
                        'trim',
                        ut\perLine(ut\surround('    ', "\n"))
                    ),
                    ut\compose(
                            ut\render(),
                            ut\prepend("\n")
                    )
                )
            ),
            ut\unlines()
        );
        $contents = $process($this->children);

        if ($this->children[0] instanceof Dd)
        {
            $contents = "<undefined>\n" . $contents;
        }

        return "\n$contents\n";
    }
}