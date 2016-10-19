<?php
namespace Unifor\Import\Html2Rest\Element;

use Unifor\Import\Html2Rest\Format;
use Unifor\Import\Html2Rest\Utility as ut;

abstract class Base
{
    protected $node;
    protected $parent;
    protected $content;
    protected $format;
    protected $children;

    abstract public function append($element);
    abstract public function render();
    abstract public function cleanEmptyNodes();

    public function __construct($node, $parent = null)
    {
        $this->node = $node;
        $this->parent = $parent;
        $this->format = $this->children = array();

        if ($this->parent)
        {
            $this->format = $this->parent->format;
        }

        $this->parseStyles();
    }

    public function getNode()
    {
        return $this->node;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function wrap($input)
    {
        return trim(wordwrap($input, 80));
    }

    protected function parseStyles()
    {
        if (!($this->node instanceof \DOMElement))
        {
            return;
        }

        $process = ut\compose(
            ut\splitBy(';'),
            ut\filter(),
            ut\map(
                ut\splitBy(':'),
                ut\map('trim'),
                ut\intercalate(':'),
                function ($x) {
                    switch ($x) {
                    case 'font-style:italic':
                        return Format::ITALIC;

                    case 'font-weight:bold':
                        return Format::BOLD;

                    case 'text-decoration:underline':
                        return Format::UNDERLINE;

                    default:
                    case 'font-variant:small-caps':
                        #echo "Unknown style: $x\n";
                        return null;
                    }
                }
            ),
            ut\filter()
        );
        $styles = $process($this->node->getAttribute('style'));

        foreach ($styles as $style)
        {
            $this->pushFormat($style);
        }
    }

    protected function pushFormat($style)
    {
        array_push($this->format, $style);
    }

    public function getReferences()
    {
        $references = array();
        if ($this->node && $this->node instanceof \DOMElement)
        {
            if ($this->node->getAttribute('id'))
            {
                $references[] = $this->node->getAttribute('id');
            }
            if ($this->node->getAttribute('name'))
            {
                $references[] = $this->node->getAttribute('name');
            }
        }

        $process = ut\compose(
            ut\filter(
                function ($x) {
                    return ($x instanceof Inline
                            || $x instanceof Plain);
                }
            ),
            ut\map(
                function ($x) {
                    return $x->getReferences();
                }
            ),
            ut\concatArray(),
            ut\prepend($references),
            ut\filter()
        );
        return $process($this->children);
    }

    public function getImages()
    {
        $images = array();
        foreach ($this->children as $child) {
            $childImages = $child->getImages();
            if ($childImages) {
                $images = array_merge($images, $childImages);
            }
        }
        return array_unique($images);
    }

    public function getDeclarations()
    {
        $declarations = array();
        foreach ($this->children as $child) {
            $childDeclarations = $child->getDeclarations();
            if ($childDeclarations) {
                $declarations = array_merge($declarations, $childDeclarations);
            }
        }
        return array_unique($declarations);
    }
}
