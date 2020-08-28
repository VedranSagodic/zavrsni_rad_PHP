<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('home');
    }
    public function aboutus()
    {
        $this->view->render('aboutus');
    }
}