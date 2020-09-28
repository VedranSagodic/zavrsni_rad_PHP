<?php

class ImageController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'image'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        // manje loše rješenje dovlačenja podataka iz baze je da ovdje se spojimo
        // i dovučemo podatke

        $images = Image::ucitajSve();
        foreach($images as $image){
            if(strlen($category->name)>20){
               $category->name=substr($category->name,0,20) . '...';
            }
        }
        $this->view->render($this->viewDir . 'index',[
            'categories'=>$categories
        ]);
    }
    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->novoView('Unesite tražene podatke',[
                'name'=>'',
            ]);
            return;
        }


        //radi se o POST i moram kontrolirati prije unosa u bazu
        // kontroler mora kontrolirati vrijednosti prije nego se ode u bazu
        $category=$_POST;

        if(strlen(trim($category['name']))===0){
            $this->novoView('Obavezno unos naziva',$_POST);
            return;
        }


        Category::dodajNovi($_POST);

        // unese i prebaci te na popis svih smjerova
        $this->index();

        //unese i ostavi te s svim podacima na trenutnoj stranici
        //$this->novoView('Smjer unesen, nastavite s unosom novih podataka',$_POST);
        
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Category::ucitaj($_GET['id']));
            return;
        }
        $category=(object)$_POST;
        if(!$this->kontrolaNaziv($category,'promjenaView')){return;};
        Category::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        Category::brisanje($_GET['id']);
        $this->index();
    }

    private function novoView($poruka,$category)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'category' => $category
        ]);
    }


    private function promjenaView($poruka,$category)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'category' => $category
        ]);
    }

    private function kontrolaNaziv($category, $view)
    {
        if(strlen(trim($category->name))===0){
            $this->$view('Obavezno unos naziva',$category);
            return false;
        }

        if(strlen(trim($category->name))>50){
            $this->$view('Dužina naziva prevelika',$category);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

}