<?php
class InicioController extends BaseController {
    private $institucionModelo;

    public function __construct()
    {
        parent::__construct();
        $this->institucionModelo = $this->model('institucion');
    }

    public function index() {
        // print_r("<pre>");
        // print_r($this->institucionModelo);
        // print_r("</pre>");
        // die();

        $nombreInstitucion = $this->institucionModelo->obtenerNombreInstitucion(1);

        $data = [
            'nombreInstitucion' => $nombreInstitucion
        ];

        $this->view('welcome', $data);
    }
}