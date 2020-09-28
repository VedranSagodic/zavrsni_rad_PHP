<?php

class ReviewController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'review'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $reviews= Review::ucitajSve();
        foreach($reviews as $review){
            if(strlen($review->comment)>20){
               $review->comment=substr($review->comment,0,20) . '...';
            }
            if($review->grade===null){
                $review->grade='Nije definirana';
            }
        }
        $this->view->render($this->viewDir . 'index',[
            'reviews'=>$reviews
        ]);
    }


    
    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $review=new stdClass();
            $review->users='';
            $review->property='';
            $review->comment='';
            $review->grade='';
            $review->posted_date='';
            $this->novoView('Unesite tražene podatke',$review);
            return;
        }
        $review=(object)$_POST;
        if(!$this->kontrolaNaziv($review,'novoView')){return;};
        Review::dodajNovi($_POST);
        $this->index();
    }



    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Review::ucitaj($_GET['id']));
            return;
        }
        $review=(object)$_POST;
        if(!$this->kontrolaNaziv($review,'promjenaView')){return;};
        Review::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Review::brisanje($_GET['id']);
        $this->index();
        
    }










    private function novoView($poruka,$review)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'review' => $review
        ]);
    }

    private function promjenaView($poruka,$review)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'review' => $review
        ]);
    }

    private function kontrolaNaziv($review, $view)
    {
        if(strlen(trim($review->comment))===0){
            $this->$view('Obavezno unos komentara',$review);
            return false;
        }

        if(strlen(trim($review->comment))>250){
            $this->$view('Dužina komentara prevelika',$review);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


}