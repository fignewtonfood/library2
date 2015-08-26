<?php
class Author
{
    private $author_name;
    private $id;

    function __construct($author_name, $id=null)
    {
        $this->author_name = $author_name;
        $this->id = $id;
    }

    function setAuthorName($new_author_name)
    {
        $this->author_name = (string) $new_author_name;
    }

    function getAuthorName()
    {
        return $this->author_name;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_authors (author_name) VALUES ('{$this->getAuthorName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($new_author_name)
    {
        $GLOBALS['DB']->exec("UPDATE t_authors SET author_name = '{$new_author_name}' WHERE id = {$this->getId()};");
        $this->setAuthorName($new_author_name);
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors WHERE id = {$this->getId()};");
    }

    static function getAll()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT * FROM t_authors;");
        $authors = array();
        foreach($returned_authors as $author) {
            $author_name = $author['author_name'];
            $id = $author['id'];
            $new_author = new Author($author_name, $id);
            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors;");
    }


}
?>
