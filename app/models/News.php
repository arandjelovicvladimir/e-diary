<?php 

class News
{
    //get all news
    public static function notifications()
    {
        $query = DB::$conn->prepare('select id, notifications from news ORDER BY id DESC');
        $query->execute([]);
        $news = $query->fetchAll(PDO::FETCH_ASSOC);
        return $news;
    }

    public static function delete_notification_by_id($not_id)
    {
        $query = 'delete from news where id=? limit 1';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$not_id]);
    }


    public static function add_notification($notification)
    {
        $query = "insert into news (notifications) values (?)";
        $query= DB::$conn->prepare($query);
        $res = $query->execute([$notification]);
        return $res;
    }

}