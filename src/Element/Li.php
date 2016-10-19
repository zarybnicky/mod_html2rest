<?php
namespace Unifor\Import\Html2Rest\Element;

class Li extends Block
{
    public function __construct($node, $parent = null)
    {
        parent::__construct($node, $parent);

        if (!($parent instanceof ListBase))
        {
            print "<li> outside of a list.\n";
        }
    }
}
