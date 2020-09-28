<?php 

class Review
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
        select  a.id, concat(b.name,b.surname) as users,
		c.id, c.name as property,
        a.comment, 
        a.grade,
        a.posted_date
        from review a
        inner join users b on a.id=b.id
        inner join property c on a.id=c.id;

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('
        
                select * from review where id=:id;

        ');
        $izraz->execute(['id'=>$sifra]);
        return $izraz->fetch();
    }



    public static function dodajNovi($review){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('insert into review (users,property,comment, grade, posted_date)
        values (:users,:property,:comment, :grade, :posted_date);');
        $izraz->execute($review);
    }



    public static function promjena($review){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('update review set 
        comment=:comment,
        grade=:grade,
        posted_date=:posted_date
        where id=:id;');
        $izraz->execute($review);
    }


    
    public static function brisanje($sifra){
        $veza = DB::getInstanca();
        $izraz = $veza->prepare('delete from review where id=:id;');
        $izraz->execute(['id'=>$sifra]);
    }


}