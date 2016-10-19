<?php
namespace Unifor\Import\Html2Rest;

require_once __DIR__ . '\\Utility.php';

class Publisher
{
    protected $settings;
    protected $invisibleElements = array(
        'Colgroup',
        'Head',
        'Iframe',
        'Link',
        'Object',
        'Script',
        'Style',
        'Title',
        '#comment'
    );

    public function __construct($settings = array())
    {
        $defaults = array();
        $this->settings = array_merge($defaults, $settings);
    }

    public function walkDom($node, $level = 0)
    {
        if ($node->nodeType == XML_TEXT_NODE && !trim($node->nodeValue)) {
            return;
        }

        $indent = str_repeat('    ', $level);

        echo $indent, '<', $node->nodeName;

        if ($node->nodeType == XML_ELEMENT_NODE) {
            $attributes = $node->attributes;
            $out = array();
            foreach ($attributes as $attribute) {
                $out[] = $attribute->name . '="' . $attribute->value . '"';
            }
            if ($out) {
                echo ' ', implode(' ', $out);
            }
        }
        echo '>';
        if ($node->nodeType == XML_TEXT_NODE && trim($node->nodeValue)) {
            echo ' (', trim($node->nodeValue), ')';
        }
        echo "\n";

        $cNodes = $node->childNodes;
        if (count($cNodes) > 0) {
            foreach ($cNodes as $cNode) {
                $this->walkDom($cNode, $level + 1);
            }
        }
    }

    public function walkTree($node, $level = 0) {
        $indent = str_repeat('    ', $level);

        if ($node instanceof Element\Text)
        {
            echo $indent, '"', $node->render(), '"', "\n";
        }
        else
        {
            echo $indent, basename(get_class($node)), "\n";
        }
        foreach ($node->getChildren() as $child) {
            $this->walkTree($child, $level + 1);
        }
    }

    public function process($html)
    {
        if (substr(trim($html), 0, 5) !== '<?xml')
        {
            $html = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $html;
        }

        $document = new \DOMDocument();
        $document->recover = true;
        $document->preserveWhiteSpace = false;
        libxml_use_internal_errors(true);
        $document->loadHTML($html);

        $root = $this->visitNode($document->documentElement);
        if ($root) {
            $root->cleanEmptyNodes();
        }
        #$this->walkDom($document->documentElement);
        #$this->walkTree($root);
        return (string) $root;
    }

    public function visitNode($node, $parent = null)
    {
        if (!$node || !$node->nodeName)
        {
            return;
        }

        $name = $node->nodeName == '#text' ? 'Text' : ucfirst($node->nodeName);

        if (array_search($name, $this->invisibleElements) !== false)
        {
            return;
        }

        if (preg_match('/h([1-6])/i', $name))
        {
            $elem = new Element\Header($node, $parent, $name[1]);
        }
        else
        {
            $class = "Unifor\\Import\\Html2Rest\\Element\\$name";

            if (!class_exists($class))
            {
                print "Unknown class $class\n";
                return;
            }

            $elem = new $class($node, $parent);
        }

        if ($node->childNodes !== null)
        {
            foreach ($node->childNodes as $child)
            {
                $childElem = $this->visitNode($child, $elem);
                if ($childElem)
                {
                    $elem->append($childElem);
                }
            }
        }

        return $elem;
    }
}
