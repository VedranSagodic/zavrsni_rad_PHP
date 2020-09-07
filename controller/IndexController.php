<?php
class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('pocetna',[
            'kljuc1' => 'Vrijednost1',
            'kljuc2' => [1,2,7,9]
        ]);
    }
    public function properties()
    {
        $this->view->render('properties');
    }

    public function onama()
    {
        $this->view->render('onama');
    }

    public function login()
    {
        $this->loginView('','Login with e-mail and password');
    }

    public function logout()
    {
        unset($_SESSION['autoriziran']);
        session_destroy();
        $this->index();
    }

    public function autorizacija()
    {

       if(!isset($_POST['email']) || !isset($_POST['lozinka'])){
            $this->login();
            return; // short curcuiting
       }

        if(strlen(trim($_POST['email']))===0){
            $this->loginView(
                trim($_POST['email']),
                'E-mail is required'
            );
            return;
        }


        if(strlen(trim($_POST['lozinka']))===0){
            $this->loginView(
                trim($_POST['email']),
                'Passwword is required'
            );
            return;
        }

        // 100% siguran da imaš email i lozinku
        $veza = DB::getInstanca();

        $izraz = $veza->prepare('select * from operater 
        where email=:email');
        $izraz->execute(['email'=>$_POST['email']]);
        $rezultat=$izraz->fetch();

        if($rezultat==null){
            $this->loginView(
                trim($_POST['email']),
                'E-mail is not found'
            );
            return;
        }

        if(!password_verify($_POST['lozinka'],$rezultat->lozinka)){
            $this->loginView(
                trim($_POST['email']),
                'Wrong password for this E-mail'
            );
            return;
        }


        // ovje sa autoriziran
        unset($rezultat->lozinka);
        $_SESSION['autoriziran']=$rezultat;
        $np = new NadzornaplocaController();
        $np->index();

       
    }

    private function loginView($email, $poruka){
        $this->view->render('login',[
            'email'=> $email,
            'poruka'=> $poruka
        ]);
    }
    

    public function test(){
        echo password_hash("a", PASSWORD_BCRYPT);
    }
}