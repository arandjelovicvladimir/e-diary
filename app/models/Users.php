<?php

class Users
{
    
    public static function get_user_by_id($id)
    {
        $query = DB::$conn->prepare('select users.id, users.first_name, users.last_name, users.username, users.password, users.cookie, users.roles_id, role.name as role_name from users join role on users.roles_id=role.id where users.id=?');
        $query->execute([$id]); 
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    
    public function get_pass_by_username($username)
    {
        $query = DB::$conn->prepare('select users.id, users.first_name, users.last_name, users.username, users.password, users.cookie, users.roles_id, role.name as role_name from users join role on users.roles_id=role.id where users.username=?');
        $query->execute([$username]); 
        $password = $query->fetch(PDO::FETCH_ASSOC);
        return $password;
    }
    
    public static function set_user_cookie($cookie, $id)
    {
        $query = 'update users set cookie=? where id=?';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$cookie, $id]);
        
    }
    
    public static function get_all_users()
    {
        $query = DB::$conn->query('select users.id, users.first_name, users.last_name, users.username, users.password, users.cookie, users.roles_id, role.name as role_name from users join role on users.roles_id=role.id');
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    
    public static function edit($first_name, $last_name, $username, $password, $role, $id)
    {
        $query = 'update users set first_name=?, last_name=?, username=?, password=?, roles_id=? where id=?';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$first_name, $last_name, $username, $password, $role, $id]);
    }
    
    public static function delete($id)
    {
        $query = 'delete from users where id=? limit 1';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$id]);
    }
    
    public static function all_roles()
    {
        $query = DB::$conn->query('select * from role');
        $roles = $query->fetchAll(PDO::FETCH_ASSOC);
        return $roles;  
    }
    
    public static function add_new_user($first_name, $last_name, $username, $password, $role_id)
    {
        $query = "insert into users (first_name, last_name, username, password, roles_id) values (?,?,?,?,?)";
        $query= DB::$conn->prepare($query);
        $res = $query->execute([$first_name, $last_name, $username, $password, $role_id]);
        return $res;
    }

    public static function get_user_by_username($username)
    {
        $query = DB::$conn->prepare('select * from users where username = ?');
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public static function get_role_id_by_name($role_name)
    {
        $query = DB::$conn->prepare('select id from role where name = ?');
        $query->execute([$role_name]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}