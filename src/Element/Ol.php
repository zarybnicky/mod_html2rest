<?php
namespace Unifor\Import\Html2Rest\Element;

class Ol extends ListBase
{
    public function __construct($node, $parent = null)
    {
        parent::__construct($node, $parent);

        $this->next = 1;
        $this->bullet = '%s. ';

        if ($start = $this->node->getAttribute('start'))
        {
            $this->next = $start;
        }
    }
}
