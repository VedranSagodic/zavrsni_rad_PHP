<?php

class Property
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.id, b.category as smjer, a.naziv,  
        concat(d.ime, \' \', d.prezime) as predavac,
        a.datumpocetka, count(e.polaznik) as polaznika from grupa a
        inner join smjer b on a.smjer=b.sifra
        left join predavac c on a.predavac = c.sifra
        left join osoba d on c.osoba = d.sifra
        left join clan e on a.sifra=e.grupa 
        group by a.sifra, b.naziv, a.naziv,  
        concat(d.ime, \' \', d.prezime),
        a.datumpocetka

        ');
        $izraz->execute();
        return $izraz->fetchAll();
       
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from property where id=:sifra

        ');
        $izraz->execute(['id'=>$sifra]);

        $entitet=$izraz->fetch();

        $izraz = $veza->prepare('
        
                select b.sifra, c.ime, c.prezime 
                from clan a inner join polaznik b
                on a.polaznik =b.sifra 
                inner join osoba c 
                on b.osoba =c.sifra 
                where a.grupa=:sifra

        ');
        $izraz->execute(['id'=>$sifra]);
        $entitet->polaznici=$izraz->fetchAll();
        return $entitet;
    }

    public static function dodajNovi($entitet)
    {
        if($entitet['category']==0){
            $entitet['category']=null;
        }
        if($entitet['build_year']==''){
            $entitet['build_year']=null;
        }else{
            $entitet['build_year']=str_replace('T',' ',$entitet['build_year']);
        }
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into property (naziv,smjer,predavac,datumpocetka)
        values (:naziv,:smjer,:predavac,:datumpocetka);');
        $izraz->execute($entitet);
    }

    public static function promjena($category)
    {
        
    }

    public static function brisanje($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from property where id=:sifra;');
        $izraz->execute(['id'=>$sifra]);
    }
}