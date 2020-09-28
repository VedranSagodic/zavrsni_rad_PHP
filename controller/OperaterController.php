<?php

class OperaterController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'operater'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index', [
            'entiteti'=>Operater::ucitajSve()
        ]);
    }

    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $entitet=new stdClass();
            $entitet->ime='';
            $entitet->prezime='';
            $entitet->uloga='';
            $entitet->lozinka='';
            $entitet->email='';
            $this->novoView('Unesite tražene podatke',$entitet);
            return;
        }
      
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'novoView')){return;};
        Operater::dodajNovi($_POST);
        $this->index();
       
    }

    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            Operater::ucitaj($_GET['sifra']));
            return;
        }
        
        $entitet=(object)$_POST;
        if(!$this->kontrolaIme($entitet,'promjenaView')){return;};
        if(!$this->kontrolaLozinka($entitet,'promjenaView')){return;};
        Operater::promjena($_POST);
        $this->index();
        
    }

    public function brisanje()
    {
        //kontrola da li je šifra došla
        Operater::brisanje($_GET['sifra']);
        $this->index();
        
    }









    private function novoView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'entitet' => $entitet
        ]);
    }

    private function promjenaView($poruka,$entitet)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'entitet' => $entitet
        ]);
    }


    private function kontrolaIme($entitet, $view)
    {
        if(strlen(trim($entitet->ime))===0){
            $this->$view('Obavezno unos imena',$entitet);
            return false;
        }

        if(strlen(trim($entitet->ime))>50){
            $this->$view('Dužina imena prevelika',$entitet);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }


    private function kontrolaLozinka($entitet, $view)
    {
        $lozinka=$entitet->lozinka;
        if ( strlen($lozinka) != 11 ) {
            $this->$view('Lozinka mora imati 11 znamenaka',$entitet);
            return false;
        }
            if ( !is_numeric($lozinka) ) {
                $this->$view('Lozinka ne smije sadržavati druge znakove osim brojeva',$entitet);
                return false;
            }
                
                $a = 10;
                
                for ($i = 0; $i < 10; $i++) {
                    
                    $a = $a + intval(substr($oib, $i, 1), 10);
                    $a = $a % 10;
                    
                    if ( $a == 0 ) { $a = 10; }
                    
                    $a *= 2;
                    $a = $a % 11;
                    
                }
                
                $kontrolni = 11 - $a;
                
                if ( $kontrolni == 10 ) { $kontrolni = 0; }
                $rezultat = $kontrolni == intval(substr($oib, 10, 1), 10);
                if(!$rezultat){
                    $this->$view('Lozinka nije valjana',$entitet);
                
                }
                return $rezultat;
    }

}