<?php
class Patron
{
    private $patron_name;
    private $phone_number;
    private $id;

    function __construct($patron_name, $phone_number, $id=null)
    {
        $this->patron_name = $patron_name;
        $this->phone_number = $phone_number;
        $this->id = $id;
    }

    function setPatronName($new_patron_name)
    {
        $this->patron_name = (string) $new_patron_name;
    }

    function setPhoneNumber($new_phone_number)
    {
        $this->phone_number = (string) $new_phone_number;
    }

    function getPatronName()
    {
        return $this->patron_name;
    }

    function getPhoneNumber()
    {
        return $this->phone_number;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_patrons (patron_name, phone_number) VALUES ('{$this->getPatronName()}', '{$this->getPhoneNumber()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($column_to_update, $new_patron_information)
    {
        $GLOBALS['DB']->exec("UPDATE t_patrons SET {$column_to_update} = '{$new_patron_information}' WHERE id = {$this->getId()};");
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_patrons WHERE id = {$this->getId()};");
    }

    static function getAll()
    {
        $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM t_patrons;");
        $patrons = array();
        foreach($returned_patrons as $patron) {
            $patron_name = $patron['patron_name'];
            $phone_number = $patron['phone_number'];
            $id = $patron['id'];
            $new_patron = new Patron($patron_name, $phone_number, $id);
            array_push($patrons, $new_patron);
        }
        return $patrons;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_patrons;");
    }


}
?>
