<?php
use Unifor\Import\Html2Rest\Publisher;

/**
 * A html->reStructuredText converter class. A wrapper around the
 * inner namespaced files.
 *
 * @author Jakub Zárybnický <jakub.zarybnicky@olc.cz>
 * @since  25. 1. 2015
 */
class Html2RestConverter
{
    protected $settings;
    protected $publisher;

    public function __construct($settings = array())
    {
        $this->publisher = new Publisher($settings);
    }

    public function process($html)
    {
        return $this->publisher->process($html);
    }
}
