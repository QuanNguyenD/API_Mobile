<?php

class CategoryModel extends DataEntry{
    
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
			
			$query = $this->qb->table(TB_CATEGORIES)
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
            "title" => "",
            "name" => "",
            "color_code" => "",
            "create_at" => date("Y-m-d H:i:s")
            
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

        $this->extendDefaults();

        $id = $this->qb->table(TB_TASKS)
            ->insert(array(
                "id" => null,
                "name" => $this->get("name"),
                "color_code" => $this->get("color_code"),
                "create_at" => $this->get("create_at")
                
            ));

        $this->set("id", $id);
        $this->markAsAvailable();
        return $this->get("id");
    }
    public function save()
    {
        return $this->isAvailable() ? $this->update() : $this->insert();
    }
    public function update()
	    {
        if (!$this->isAvailable())
            return false;

        $this->extendDefaults();

        $id = $this->qb->table(TB_TASKS)
            ->where("id", "=", $this->get("id"))
            ->update(array(
                "name" => $this->get("name"),
                "color_code" => $this->get("color_code"),
                "create_at" => $this->get("create_at")
            ));

        return $this;
	}
    public function getAll(){
        $query = $this->qb->table(TB_CATEGORIES)->select("*");
        return $query;
    }



}




?>