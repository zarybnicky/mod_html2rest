<?php
namespace Unifor\Import\Html2Rest\Element;

class Img extends Inline
{
    public function render()
    {
        $src = $this->node->getAttribute('src');
        return "|$src|";
    }
    public function getImages()
    {
        $src = $this->node->getAttribute('src');
        $contents = ".. |$src| image:: $src";
        if ($this->node->getAttribute('alt'))
        {
            $contents .= "\n   :alt: " . $this->node->getAttribute('alt');
        }
        return array($contents);
    }
    public function isEmpty()
    {
        return false;
    }
}
