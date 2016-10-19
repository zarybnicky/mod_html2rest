<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Plain extends Block
{
    public function append($element)
    {
        $this->children[] = $element;
    }

    public function render()
    {
        $thisVar = $this;
        $processSegment = function ($xs) use ($thisVar) {
            $process = ut\mapRenderConcat();
            return $thisVar->wrap($process($xs));
        };

        $hasBr = ut\any(
            ut\isA('Unifor\Import\Html2Rest\Element\Br'),
            $this->children
        );

        if (!$hasBr) {
            return $processSegment($this->children);
        }

        $process = ut\compose(
            ut\splitByClass('Br'),
            ut\map(
                $processSegment,
                ut\perLine(
                    'trim',
                    ut\prepend('| ')
                )
            ),
            ut\unlines(),
            ut\surround("\n", "\n")
        );
        return $process($this->children);
    }

    public function cleanEmptyNodes()
    {
        parent::cleanEmptyNodes();

        $allEmpty = true;
        foreach ($this->children as $child)
        {
            $allEmpty &= $child->isEmpty();
       }
        if ($allEmpty)
        {
            $this->children = array();
        }
        if ($this->children)
        {
            $first = reset($this->children);
            if ($first->isEmpty()) {
                array_shift($this->children);
            }
        }
        if ($this->children && !trim(end($this->children)->render()))
        {
            $last = end($this->children);
            if ($last->isEmpty()) {
                array_pop($this->children);
            }
        }
    }
}