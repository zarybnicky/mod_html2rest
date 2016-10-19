<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class P extends Block
{
    public function render()
    {
        $process = ut\mapRenderConcat();
        $contents = $process($this->children);

        if ($this->children)
        {
            if (reset($this->children) instanceof Plain)
            {
                $contents = "\n" . ltrim($contents);
            }
            if (end($this->children) instanceof Plain)
            {
                $contents = rtrim($contents) . "\n";
            }
        }

        $refProcess = ut\compose(
            ut\map(ut\surround('.. _', ':')),
            ut\unlines()
        );
        $references = $refProcess($this->getReferences());

        if ($references)
        {
            $contents = "\n$references\n$contents";
        }

        return $contents;
    }
}
