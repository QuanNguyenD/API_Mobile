<?php

class UserMOdel extends DataEntry{
    protected $db;
    protected $qb;
    public function __construct($uniqid=0)
    {
        
        $this->db = App::getConnection();     
		$this->qb = new \Pixie\QueryBuilder\QueryBuilderHandler($this->db);
        parent::__construct();
        $this->select($uniqid);
    }
    public function select($uniqid)
	{
		if (is_int($uniqid) || ctype_digit($uniqid)) {
			$col = $uniqid > 0 ? "id" : null;
		} else if (filter_var($uniqid, FILTER_VALIDATE_EMAIL)) {
			$col = "email";
		}
		else 
		{
			$col = "phone";
		}

		if ($col) {
			
			$query = $this->qb->table(TB_USERS)
						->where($col, "=", $uniqid)
						->limit(1)
						->select("*");
			if ($query->count() == 1) {
				$resp = $query->get();
				$r = $resp[0];

				foreach ($r as $field => $value)
					$this->set($field, $value);

				$this->is_available = true;
			} else {
				$this->data = array();
				$this->is_available = false;
			}
		}

		return $this;
	}

    public function extendDefaults()
    {
        $defaults = array(
            "username"=>"",
            "email" => "",
            "phone" => "",
            "password" => "",
            "name" => "",
            "description" => "",
            "price" => 0,
            "role" => "admin",
            "active" => "1",
            "avatar" => "",
            "create_at" => date("Y-m-d H:i:s"),
            "update_at" => date("Y-m-d H:i:s"),
            "speciality_id" => "",
            "room_id" => "",
            "recovery_token" => ""
        );


        foreach ($defaults as $field => $value) {
            if (is_null($this->get($field)))
                $this->set($field, $value);
        }
    }

    public function insert()
    {
        if ($this->isAvailable())
            return false;

        //$this->extendDefaults();
        // Chèn dữ liệu từ $this->data vào bảng
        $insertId = $this->qb->table(TB_USERS)
                            ->insert($this->data);

        // Nếu chèn thành công, lấy lại ID và cập nhật trong đối tượng
        if ($insertId) {
            $this->set("id", $insertId);  // Cập nhật ID mới được tạo vào đối tượng
            $this->is_available = true;   // Đánh dấu rằng bản ghi này tồn tại
            return $insertId;
        }

        return false;
    }


}



?>