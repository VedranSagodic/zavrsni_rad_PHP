<?php

class PropertyController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'property'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        // manje loše rješenje dovlačenja podataka iz baze je da ovdje se spojimo
        // i dovučemo podatke

        $properties = Property::ucitajSve();
        foreach($properties as $property){
            if(strlen($property->name)>20){
               $property->name=substr($property->name,0,20) . '...';
            }
            if($property->price===null){
                $property->price='Not defined';
            }
        }

        $this->view->render($this->viewDir . 'index',[
            'property'=>$properties
        ]);
    }

    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->novoView('Unesite tražene podatke',[
                'name'=>'',
                'location'=>'',
                'room_number'=>'',
                'bathroom_number'=>'',
                'total_surface'=>'',
                'build_year'=>'',
                'condition_'=>'',
                'price'=>'',
                'description'=>'',
            ]);
            return;
        }


        //radi se o POST i moram kontrolirati prije unosa u bazu
        // kontroler mora kontrolirati vrijednosti prije nego se ode u bazu
        $property=$_POST;

        if(strlen(trim($property['name']))===0){
            $this->novoView('Obavezno unos naziva',$_POST);
            return;
        }


        Property::dodajNovi($_POST);

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

    private function novoView($poruka,$property)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'property' => $property
        ]);
    }

}