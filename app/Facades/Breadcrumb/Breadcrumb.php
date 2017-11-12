<?php

namespace App\Facades\Breadcrumb;

class Breadcrumb {

    /**
     * breacdcrumb
     * @var array
     */
    protected $paths = [];

    /**
     * Set root node
     */
    public function setRoot($paths) {
        foreach ($paths as list($url, $text)) {
            self::add($text, $url);
        }
    }

    /**
     * Add a node
     */
    public function add($text, $url = null) {
        $this->paths[] = [
            'url' => $url,
            'text' => $text
        ];
    }

    /**
     * Get list nodes to render
     */
    public function get() {
        return $this->paths;
    }

    public function render($class = 'nav-breadcrumb') {
        $render = '';
        if ($this->paths) {
            $render = '<ol class="breadcrumb '. $class .'">';
            foreach ($this->paths as $path) {
                $render .= '<li>';
                if (isset($path['url']) && ($url = $path['url'])) {
                    $render .= '<a href="' . $url . '">' . $path['text'] . '</a>';
                } else {
                    $render .= $path['text'];
                }
                $render .= '</li>';
            }
            $render .= '</ol>';
        }
        return $render;
    }

}
