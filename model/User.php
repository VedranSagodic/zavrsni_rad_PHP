<?php 

class User
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from users;');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($user){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into users (name,surname,password,e_mail,phone,address)
        values (:name,:surname,:password,:e_mail,:phone,:address);');
        $izraz->execute($user);
    }

}