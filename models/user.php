<?php
$script_dir = dirname(__FILE__);
require_once $script_dir."/../utils/db_utils.php";

class User{
    public static string $__tablename__ = "user";

    private static string $__fields__ = "id, username, password, email, name, role, status, date_of_birth, gender, blood_group
    creation_time, modification_time, creator_id, modificator_id"; 
    
    private int $id;
    private string $uid;
    public string $username;
    private string $password;
    public string $email;
    public string $name;

    public string $role;
    public string $status;

    public string $date_of_birth;
    public string $gender;
    public string $blood_group;

    private string $creation_time;
    private string $modification_time;

    private int $creator_id;
    private int $modifier_id;

    private mysqli $__conn;

    public function __construct(mysqli $conn){
        $this->__conn = $conn;
    }

    public function get_id(): int{
        return $this->id;
    }
    public function creation_time(): string{
        return $this->creation_time;
    }
    public function modification_time(): string{
        return $this->modification_time;
    }
    public function creator_id(): int{
        return $this->creator_id;
    }
    public function modifier_id(): int{
        return $this->modifier_id;
    }

    public function set_password(string $password): void{
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function verify_password(string $password): bool{
        return password_verify($password, $this->password);
    }

    public function set_uid(): void{
        $this->uid = uniqid(bin2hex(random_bytes(20)), true);
    }
    public function get_uid(): string{
        return $this->uid;
    }

    function get_map(): array{
        $map = json_decode(json_encode($this), true);
        unset($map['__conn']);
        
        if (isset($this->id)) $map["id"] = $this->id;
        if (isset($this->creation_time)) $map["creation_time"] = $this->creation_time;
        if (isset($this->modification_time)) $map["modification_time"] = $this->modification_time;
        if (isset($this->creator_id)) $map["creator_id"] = $this->creator_id;
        if (isset($this->modifier_id)) $map["modifier_id"] = $this->modifier_id;
        return $map;
    }

    public function insert(int $creator_id){
        if($creator_id>0) $this->creator_id = $creator_id;
        $this->creation_time = date("Y-m-d H:i:s");
        
        $map = $this->get_map();
        $map["password"] = $this->password;
        $this->set_uid();
        $map["uid"] = $this->uid;
        $this->id = insert_into_table($this->__conn, self::$__tablename__, $map);
    }


    private function from_assoc_to_obj(array $assoc): void{
        foreach($assoc as $key => $value){
            if($value != null) $this->$key = $value;
        }    
    }

    public function sync(){
        $res_assoc = select_from_table($this->__conn, self::$__tablename__, null, "id=".$this->id);
        $res_assoc = $res_assoc->fetch_assoc();
        
        $this->from_assoc_to_obj($res_assoc);
    }

    public function update(int $modifier_id, array $update_assoc = null){
        if($modifier_id>0) $this->modifier_id = $modifier_id;
        $this->modification_time = date("Y-m-d H:i:s");
        
        update_table($this->__conn, self::$__tablename__, $this->get_map(), "id=".$this->id);
    }

    public function delete(){
        delete_from_table($this->__conn, self::$__tablename__, "id=".$this->id);
    }
    
    public static function get_by_id(mysqli $conn, int $id): User{
        $user = new User($conn);
        $user->id = $id;
        $user->sync();
        return $user;
    }

    public static function get_by_username(mysqli $conn, string $username): User{
        $user = new User($conn);
        $res_assoc = select_from_table($conn, self::$__tablename__, null, "username='$username'");
        $res_assoc = $res_assoc->fetch_assoc();
        $user->from_assoc_to_obj($res_assoc);
        return $user;
    }

    public static function get_by_email(mysqli $conn, string $email): User{
        $user = new User($conn);
        $res_assoc = select_from_table($conn, self::$__tablename__, null, "email='$email'");
        $res_assoc = $res_assoc->fetch_assoc();
        $user->from_assoc_to_obj($res_assoc);
    }

    public static function create_user_from_assoc(mysqli $conn, array $assoc_arr, int $creator_id){
        $user = new User($conn);
        $user->from_assoc_to_obj($assoc_arr);
        $user->set_password($assoc_arr["password"]);
        $user->insert($creator_id);
        return $user;
    }

    public static function create_table($conn){
        $query = "create table `".self::$__tablename__."`(
            id int auto_increment primary key,
            uid varchar(255) not null,
            name varchar(80),
            username varchar(20) unique not null,
            email varchar(30) unique not null,
            password varchar(255) not null,
            role varchar(20) default 'general',
            status varchar(20) default 'active',

            date_of_birth date,
            gender varchar(10),
            blood_group varchar(10),

            creation_time datetime,
            modification_time datetime,

            creator_id int,
            modifier_id int,
            foreign key(creator_id) references `user`(id),
            foreign key(modifier_id) references `user`(id)
        );";
        
        if($conn->query($query)){
            echo "[*] Table created succesessfully!<br>";
        }
        else{
            die("[!] Error creating table! " . $conn->error);
        }
    }

    public static function delete_table($conn){
        delete_table($conn, self::$__tablename__);
    }
}
?>
