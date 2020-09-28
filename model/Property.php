<?php

class Property
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.id, concat (b.name,b.surname) as users,c.name as category,
        d.comment as review, e.image_files as image,
        a.name, 
        a.location,
        a.room_number,
        a.bathroom_number,
        a.total_surface,
        a.build_year,
        a.condition_,
        a.price,
        a.description from property a
        inner join users b on a.id=b.id
        inner join category c on a.id=c.id
        inner join review d on a.id=d.id
        inner join image e on a.id=e.id ;

        ');
        $izraz->execute();
        return $izraz->fetchAll();
       
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select * from property where id=:id;

        ');
        $izraz->execute(['id'=>$sifra]);
    }
    public static function dodajNovi($entitet)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into property (users,category, name, location,room_number,bathroom_number,total_surface,build_year,condition_,price,description,)
        values (:users,:category, :name, :location,:room_number,:bathroom_number,:total_surface,:build_year,:condition_,:price,:description,);');
        $izraz->execute($entitet);
    }

    public static function promjena($property)
    {
        
    }

    public static function brisanje($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from property where id=:id;');
        $izraz->execute(['id'=>$sifra]);
    }
}