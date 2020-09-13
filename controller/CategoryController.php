<?php

class CategoryController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'category'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        // manje loše rješenje dovlačenja podataka iz baze je da ovdje se spojimo
        // i dovučemo podatke

        $categories = Category::ucitajSve();
        foreach($categories as $category){
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
        
    }

    public function brisanje()
    {
        
    }

    private function novoView($poruka,$category)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'category' => $category
        ]);
    }

}
