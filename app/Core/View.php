<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 23.07.16
 * Time: 1:00
 */

namespace Core;


final class View
{
    private $template_path;
    private $template_ext;
    //private $data;

    /**
     * View constructor.
     * @param $template_path
     * @param $template_ext
     */
    public function __construct($template_path = (ROOT.'app'.DS.'views'.DS), $template_ext = '.html'/*, $data = null*/) {
        $this->template_path = $template_path;
        $this->template_ext = $template_ext;
    //    $this->data = $data;
    }


    public function render($template_name, $data = []) {
        extract($data);
        //$r = $data;
        //$this->data;
        ob_start();
        include ($this->template_path . $template_name . $this->template_ext);
        return ob_get_clean();
    }
}