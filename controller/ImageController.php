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
            if(strlen($image->image_files)>20){
               $image->image_files=substr($image->image_files,0,20) . '...';
            }
        }
        $this->view->render($this->viewDir . 'index',[
            'images'=>$images
        ]);
    }
    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->novoView('Unesite tražene podatke',[
                'image_files'=>'',
            ]);
            return;
        }


        //radi se o POST i moram kontrolirati prije unosa u bazu
        // kontroler mora kontrolirati vrijednosti prije nego se ode u bazu
        $image=$_POST;

        if(strlen(trim($image['image_files']))===0){
            $this->novoView('Obavezno unos fotografije',$_POST);
            return;
        }


        Image::dodajNovi($_POST);

        // unese i prebaci te na popis svih smjerova
        $this->index();

        //unese i ostavi te s svim podacima na trenutnoj stranici
        //$this->novoView('Smjer unesen, nastavite s unosom novih podataka',$_POST);
        
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Image::ucitaj($_GET['id']));
            return;
        }
        $image=(object)$_POST;
        if(!$this->kontrolaNaziv($image,'promjenaView')){return;};
        Image::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        Image::brisanje($_GET['id']);
        $this->index();
    }

    private function novoView($poruka,$image)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'image' => $image
        ]);
    }


    private function promjenaView($poruka,$image)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'image' => $image
        ]);
    }

    private function kontrolaNaziv($image, $view)
    {
        if(strlen(trim($image->image_files))===0){
            $this->$view('Obavezno unos fotografije',$image);
            return false;
        }

        if(strlen(trim($image->image_files))>50){
            $this->$view('Dužina naziva prevelika',$image);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

}