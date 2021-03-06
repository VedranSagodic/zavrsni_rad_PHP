<?php 

class Category
{

    public static function ucitajSve($poCemu='name', $uzlaznoSilazno='asc')
    {
        $order = $poCemu . ' ' . $uzlaznoSilazno;
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.id, a.name, count(b.id ) as category
                from category a left join property b on
                a.id=b.category group by a.id, a.name;

        ');
        $izraz->bindParam('slozi',$order);
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select * from category where id=:id;

        ');
        $izraz->execute(['id'=>$sifra]);
        return $izraz->fetch();
    }



    public static function dodajNovi($category){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into category (name)
        values (:name);');
        $izraz->execute($category);
    }



    public static function promjena($category){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('update category set 
        name=:name
        where id=:id;');
        $izraz->execute($category);
    }


    
    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from category where id=:id;');
        $izraz->execute(['id'=>$sifra]);
    }


}