<?php


namespace Classes;


final class View
{
    private $templates_path;
    private $templates_ext;

    /**
     * View constructor.
     * @param $templates_path
     * @param $templates_ext
     */
    public function __construct($templates_path, $templates_ext) {
        $this->templates_path = $templates_path;
        $this->templates_ext = $templates_ext;
    }

    public function render($template_name, $data = []) {
        extract($data);
        ob_start();
        include ($this->templates_path . $template_name . $this->templates_ext);
        return ob_get_clean();
    }

}