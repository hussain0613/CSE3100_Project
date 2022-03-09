<?php
$script_dir = dirname(__FILE__);
require_once $script_dir."/../utils/db_utils.php";

class DoctorSession{
    public static string $__tablename__ = "doctor_session";

    
    private int $id;

    public int $organization_id, $seat;
    public string $doctors_name, $time_slot, $day, $specialization, $bmdc_reg_no; 

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
    
    public static function get_by_id(mysqli $conn, int $id): DoctorSession{
        $doc_sess = new DoctorSession($conn);
        $doc_sess->id = $id;
        $doc_sess->sync();
        return $doc_sess;
    }

    public static function create_doctorSession_from_assoc(mysqli $conn, array $assoc_arr, int $creator_id){
        $doc_sess = new DoctorSession($conn);
        $doc_sess->from_assoc_to_obj($assoc_arr);
        $doc_sess->insert($creator_id);
        return $doc_sess;
    }

    public static function get_multiple(mysqli $conn, string $where = null): array{
        $doc_sesss = [];
        $res = select_from_table($conn, self::$__tablename__, null, $where);
        
        $res_assoc = $res->fetch_assoc();
        while($res_assoc){
            $doc_sess = new DoctorSession($conn);
            $doc_sess->from_assoc_to_obj($res_assoc);
            $doc_sesss[] = $doc_sess;

            $res_assoc = $res->fetch_assoc();
        }
        return $doc_sesss;
    }

    public static function create_table($conn){
        $query = "create table if not exists `".self::$__tablename__."`(
            id int auto_increment primary key,
            organization_id int not null,

            doctors_name varchar(255) not null,
            specialization varchar(255) not null,
            bmdc_reg_no varchar(255) not null,

            time_slot time not null,
            day varchar(20) not null,
            
            fee int not null,

            seat int not null,

            creation_time datetime,
            modification_time datetime,

            creator_id int not null,
            modifier_id int,
            foreign key(creator_id) references `user`(id),
            foreign key(modifier_id) references `user`(id),
            foreign key(organization_id) references `organization`(id),
            unique(bmdc_reg_no, day, time_slot)
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
