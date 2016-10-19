<?php
namespace Unifor\Import\Html2Rest\Element;

class Body extends Block
{
    public function render()
    {
        $contents = parent::render();

        $declarations = $this->getDeclarations();
        if ($declarations) {
            $contents = implode("\n", $declarations) . "\n" . $contents;
        }

        $images = $this->getImages();
        if ($images) {
            $contents .=  "\n" . implode("\n", $images) . "\n";
        }
        return $contents;
    }
}