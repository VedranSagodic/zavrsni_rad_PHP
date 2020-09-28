<?php 

class Image
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select a.id, a.name, count(b.id ) as category
                from category a left join property b on
                a.id=b.category group by a.id, a.name;

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select * from image where id=:id;

        ');
        $izraz->execute(['id'=>$sifra]);
        return $izraz->fetch();
    }



    public static function dodajNovi($image){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into image (image_files)
        values (:image_files);');
        $izraz->execute($image);
    }



    public static function promjena($image){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('update image set 
        image_files=:image_files
        where id=:id;');
        $izraz->execute($image);
    }


    
    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from image where id=:id;');
        $izraz->execute(['id'=>$sifra]);
    }


}