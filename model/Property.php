<?php 

class Property
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('select * from property;');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($property){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into property (name,location,room_number,bathroom_number,total_surface,build_year,condition_,price,description)
        values (:name,:location,:room_number,:bathroom_number,:total_surface,:build_year,:condition_,:price,:description);');
        $izraz->execute($property);
    }


}