<?php

class UserController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'user'
    . DIRECTORY_SEPARATOR;

    public function index()
    {

        $users = User::ucitajSve();
        foreach($users as $user){
            if(strlen($user->surname)>20){
               $user->surname=substr($user->surname,0,20) . '...';
            }
            if($user->password===null){
                $user->password='Nije definirana';
            }
            if($user->e_mail===null){
                $user->e_mail='Nije definirana';
            }
            if($user->phone===null){
                $user->phone='Nije definirana';
            }    
            if($user->address===null){
                $user->address='Nije definirana';
            }
        }
        $this->view->render($this->viewDir . 'index',[
            'users'=>$users
        ]);
    }


    
    public function novo()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $user=new stdClass();
            $user->name='';
            $user->surname='';
            $user->password='';
            $user->e_mail='';
            $user->phone='';
            $user->address='';
            $this->novoView('Unesite tražene podatke',$user);
            return;
        }
        $user=(object)$_POST;
        if(!$this->kontrolaNaziv($user,'novoView')){return;};
        User::dodajNovi($_POST);
        $this->index();
    }



    
    public function promjena()
    {
        if ($_SERVER['REQUEST_METHOD']==='GET'){
            $this->promjenaView('Promjenite željene podatke',
            User::ucitaj($_GET['sifra']));
            return;
        }

        $user=(object)$_POST;
        if(!$this->kontrolaNaziv($user,'promjenaView')){return;};
        User::promjena($_POST);
        $this->index();
    }

    public function brisanje()
    {
              //kontrola da li je šifra došla
              User::brisanje($_GET['sifra']);
              $this->index();  
    }








    private function novoView($poruka,$user)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'user' => $user
        ]);
    }

    private function promjenaView($poruka,$user)
    {
        $this->view->render($this->viewDir . 'promjena',[
            'poruka'=>$poruka,
            'user' => $user
        ]);
    }

    private function kontrolaNaziv($user, $view)
    {
        if(strlen(trim($user->name))===0){
            $this->$view('Obavezno unos imena',$user);
            return false;
        }

        if(strlen(trim($user->name))>50){
            $this->$view('Dužina imena prevelika',$user);
            return false;
        }
        // na kraju uvijek vrati true
        return true;
    }

}
