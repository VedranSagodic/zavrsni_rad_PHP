<?php

class PropertyController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'property'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Property::ucitajSve()
        ]);
    }


    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->name='';
            $entitet->users=0;
            $entitet->category=0;
            $entitet->location='';
            $entitet->room_number='';
            $entitet->bathroom_number='';
            $entitet->total_surface='';
            $entitet->build_year='';
            $entitet->condition_='';
            $entitet->price='';
            $entitet->description='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
        $entitet=(object)$_POST;
        if(!$this->kontrolaUsers($entitet,'novoView')){return;};
        if(!$this->kontrolaCategory($entitet,'novoView')){return;};
        Property::dodajNovi($_POST);
        $this->index();
    }



    public function promjena()
    {
        $entitet = Property::ucitaj($_GET['id']);
        if ($_SERVER['REQUEST_METHOD']==='GET'){

            $this->promjenaView('Promjenite željene podatke',
            $entitet);
            return;
        }
        $entitet=(object)$_POST;
        if(!$this->kontrolaUsers($entitet,'novoView')){return;};
        if(!$this->kontrolaCategory($entitet,'novoView')){return;};
        Property::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        Property::brisanje($_GET['id']);
        $this->index();
    }










    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'categories' => Category::ucitajSve(),
            'users' => User::ucitajSve()
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet,
            'categories' => Category::ucitajSve(),
            'users' => User::ucitajSve()
        ]);
    }

   
    private function kontrolaCategory($entitet, $view)
    {
        if($entitet->category==0){
            $this->$view('Obavezno odabir kategorije',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

    private function kontrolaUsers($entitet, $view)
    {
        if(strlen(trim($entitet->users))===0){
            $this->$view('Obavezno unos korisnika',$entitet);
            return false;
        }

        if(strlen(trim($entitet->users))>50){
            $this->$view('Dužina naziva prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


}