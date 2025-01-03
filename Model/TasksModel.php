<?php

class TasksModel extends DataEntry{
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
			
			$query = $this->qb->table(TB_TASKS)
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
            "description" => "",
            "status" => "",
            "due_date" => "",
            "priority" => "",
            "category_id" => "",
            "create_at" => date("Y-m-d H:i:s"),
            "update_at" => date("Y-m-d H:i:s"),
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
                "title" => $this->get("title"),
                "description" => $this->get("description"),
                "status" => $this->get("status"),
                "due_date" => $this->get("due_date"),
                "priority" => $this->get("priority"),
                "category_id" => $this->get("category_id"),
                "create_at" => $this->get("create_at"),
                "update_at" => $this->get("update_at")
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
                "title" => $this->get("title"),
                "description" => $this->get("description"),
                "status" => $this->get("status"),
                "due_date" => $this->get("due_date"),
                "priority" => $this->get("priority"),
                "category_id" => $this->get("category_id"),
                "create_at" => $this->get("create_at"),
                "update_at" => $this->get("update_at")
            ));

        return $this;
	}

    public function getAll(){
        $query = $this->qb->table(TB_TASKS)->leftJoin(TB_CATEGORIES,TB_CATEGORIES.".id","=",TB_TASKS.".category_id")
        ->select([
            TB_TASKS.".*",
            $this->qb->raw(TB_CATEGORIES.".id as category_id"),
            $this->qb->raw(TB_CATEGORIES.".name as category_name"),

        ]);
        return $query;
    }
    public function add($title){
        $query = $this->qb->table(TB_TASKS)->where(TB_TASKS.".title","=", $title);
        return $query;
    }
    



}


?>