<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Utility as ut;

class Block extends Base
{
    public function render()
    {
        $refProcess = ut\compose(
            ut\map(ut\surround('.. _', ':')),
            ut\unlines()
        );
        $references = $refProcess($this->getReferences());

        $process = ut\mapRenderUnlines();
        $contents = $process($this->children);

        if ($references)
        {
            $contents = $references . "\n\n" . $contents;
        }
        return $contents;
    }

    public function append($element)
    {
        if ($element instanceof Inline)
        {
            if (
                $this->children &&
                $this->children[count($this->children) - 1] instanceof Plain
            ) {
                $this->children[count($this->children) - 1]->append($element);
            }
            else
            {
                $plain = new Plain(null);
                $plain->append($element);
                $this->children[] = $plain;
            }
        } elseif (
            stripos($element->getNode()->getAttribute('class'), 'note') !== false
            && stripos($element->getNode()->getAttribute('class'), 'has-note') === false
            && stripos($element->getNode()->getAttribute('class'), 'after-note') === false
        ) {
            $note = new Note(null);
            $note->setIcon($element);
            $this->children[] = $note;
        }
        elseif (stripos($element->getNode()->getAttribute('class'), 'has-note') !== false)
        {
            foreach (array_reverse($this->children) as $child) {
                if ($child instanceof Note) {
                    $child->setContent($element);
                    return;
                }
            }
            echo "Note outside of a note block.\n";
            $this->children[] = $element;
        }
        else
        {
            $this->children[] = $element;
        }
    }

    public function cleanEmptyNodes()
    {
        foreach ($this->children as $child)
        {
            $child->cleanEmptyNodes();
        }

        $this->children = array_filter(
            $this->children,
            function ($item) {
                return !$item->isEmpty();
            }
        );
    }

    public function isEmpty()
    {
        return !$this->children && !$this->getReferences();
    }
}