<?php

class Controller
{

    /**
     * Load View
     * @param string $view
     * @param array $data
     */

    public function view($view, $data = [])
    {
        if (is_array($data)) {
            extract($data);
        }

        $view = str_replace('.', '/', $view);

        $filename = "app/views/" . $view . ".php";
        if (file_exists($filename)) {
            require $filename;
        } else {
            //si el archivo de la vista no existe
            die('La vista no existe');
        }
    }

    /**
     * Load Model
     * @param string $model
     */

    public function model($model)
    {
        $filename = "app/models/" . ucfirst($model) . ".php";

        if (file_exists($filename)) {
            require $filename;

            return new $model;
        }

        return false;
    }
}
