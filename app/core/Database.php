<?php

//Using traits instead of classes allows to reuse code in multiple classes
//for example, this way we don't extend Database, we simply say, use Database. see "Model.php";
Trait Database
{
    private function connect()
    {
        $string = "mysql:hostname=".DBHOST.";dbname=".DBNAME;
        return $con = new PDO($string, DBUSER, DBPASS);
    }

    //get all results
    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check= $stm->execute($data);

        if($check)
        {
            return true;
        }
        
        return false;
    }

    //get one row
    public function get_row($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check= $stm->execute($data);

        if($check)
        {
            return $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($result) && count($result) == 1)
            {
                return $result[0];
            }
        }
        
        return false;
    }
}

