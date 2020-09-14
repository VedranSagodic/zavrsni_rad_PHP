<?php

class UserController extends AutorizacijaController
{

    private $viewDir = 'privatno' 
    . DIRECTORY_SEPARATOR 
    . 'user'
    . DIRECTORY_SEPARATOR;

    public function index()
    {
        // manje loše rješenje dovlačenja podataka iz baze je da ovdje se spojimo
        // i dovučemo podatke

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
            $this->novoView('Unesite tražene podatke',[
                'name'=>'',
                'surname'=>'',
                'password'=>'',
                'e_mail'=>'',
                'phone'=>'',
                'address'=>'',
            ]);
            return;
        }


        //radi se o POST i moram kontrolirati prije unosa u bazu
        // kontroler mora kontrolirati vrijednosti prije nego se ode u bazu
        $user=$_POST;

        if(strlen(trim($user['name']))===0){
            $this->novoView('Obavezno unos imena',$_POST);
            return;
        }


        User::dodajNovi($_POST);

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

    private function novoView($poruka,$user)
    {
        $this->view->render($this->viewDir . 'novo',[
            'poruka'=>$poruka,
            'user' => $user
        ]);
    }

}
