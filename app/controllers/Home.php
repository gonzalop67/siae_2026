<?php

class Home extends Controller
{
    public function index()
    {
        $data = [
            'nombreInstitucion' => "UNIDAD EDUCATIVA SALAMANCA"
        ];
        $this->view('welcome', $data);
    }
}
