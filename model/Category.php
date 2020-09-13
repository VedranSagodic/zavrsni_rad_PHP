<?php 

class Category
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from category;');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($category){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into category (name)
        values (:name);');
        $izraz->execute($category);
    }

}