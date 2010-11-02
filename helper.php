<?php

echo '<pre>';

$data = array(
    'displayLink',
    'htmlSnippet',
    'htmlTitle',
    'link',
    'pagemap',
    'snippet',
    'title'
);

foreach($data as $prop)
{
    

    echo '
    /**
     * @var string
     */
    protected '.$prop.';
    ';
}