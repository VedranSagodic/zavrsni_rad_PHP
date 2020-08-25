<?php

class View
{
    private $layout;

    public function __construct($layout='template')
    {
        $this->layout=$layout;
    }

    public function render($page,$parameters=[])
    {
        ob_start();
        extract($parameters);
        include BP. 'view' . DIRECTORY_SEPARATOR . $page . '.phtml';
        $content = ob_get_clean();
        include BP . 'view' . DIRECTORY_SEPARATOR . $this->layout . '.phtml';
    }
}