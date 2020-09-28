<?php 

class User
{

    public static function ucitajSve($poCemu='name', $uzlaznoSilazno='asc')
    {
        $order = $poCemu . ' ' . $uzlaznoSilazno;
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.id, a.name, a.surname, a.password, a.e_mail,
        a.phone, a.address, 
        count(b.id ) as property
        from users a left join property b on
        a.id=b.users group by a.id, a.name, a.surname, 
        a.password, a.e_mail, a.phone, a.address order by :slozi;
        
        ');
        $izraz->bindParam('slozi',$order);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select * from users where id=:id;

        ');
        $izraz->execute(['id'=>$sifra]);
        return $izraz->fetch();
    }

    public static function dodajNovi($user){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into users (name,surname,password,e_mail,phone,address)
        values (:name,:surname,:password,:e_mail,:phone,:address);');
        $izraz->execute($user);
    }

    public static function promjena($user){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('update users set 
        name=:name,
        surname=:surname,
        password=:password,
        e_mail=:e_mail,
        phone=:phone,
        address=:address
        where id=:id;');
        $izraz->execute($user);
    }


    
    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from users where id=:id;');
        $izraz->execute(['id'=>$sifra]);
    }
}