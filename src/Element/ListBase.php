<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class ListBase extends Block
{
    protected $next;
    protected $bullet;

    public function getNextPrefix()
    {
        if ($this->next)
        {
            return sprintf($this->bullet, $this->next++);
        }
        else
        {
            return $this->bullet;
        }
    }

    public function render()
    {
        $thisVar = $this;
        $process = ut\compose(
            ut\map(
                ut\render(),
                'trim',
                ut\lines(),
                function ($x) use ($thisVar) {
                    $process = ut\hang($thisVar->getNextPrefix());
                    return $process($x);
                },
                ut\unlines()
            ),
            ut\unlines(),
            ut\surround("\n\n", "\n\n")
        );
        return $process($this->children);
    }
}
