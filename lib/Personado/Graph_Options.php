<?php
/**
 * Created by IntelliJ IDEA.
 * User: hitori
 * Date: 13.12.14
 * Time: 19:32
 */

namespace Personado;


class Graph_Options {
    public $dataSource;
    public $title;
    public $width;
    public $height;

    function __construct(&$dataSource, $title = null, $width = null, $height = null)
    {
        $this->width = $width
            ? $width
            : (isset($dataSource->width)
                ? $dataSource->width
                : null
            );
        $this->height = $height
            ? $height
            : (isset($dataSource->height)
                ? $dataSource->height
                : null
            );
        $this->title = $title
            ? $title
            : (isset($dataSource->title)
                ? $dataSource->title
                : null
            );
        $this->dataSource = $dataSource;
    }

}