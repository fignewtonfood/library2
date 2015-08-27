<?php
class Book
{
    private $title;
    private $id;

    function __construct($title, $id=null)
    {
        $this->title = $title;
        $this->id = $id;
    }

    function setTitle($new_title)
    {
        $this->title = (string) $new_title;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_books (title) VALUES ('{$this->getTitle()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($new_title)
    {
        $GLOBALS['DB']->exec("UPDATE t_books SET title = '{$new_title}' WHERE id = {$this->getId()};");
        $this->setTitle($new_title);
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_books WHERE id = {$this->getId()};");
    }

    ///////////
    ///////////////All tests pass up to this point. Recieving errors for tests pertaining to functions commented-out below.
    //////////


    function addAuthor($author)
    {
        $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$author->getId()}, {$this->getId()});");
    }

    function getAuthor()
    {
        $query = $GLOBALS['DB']->query("SELECT author_id FROM authors_books WHERE book_id = {$this->getId()};");
        $author_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $authors = array();
        foreach ($author_ids as $id) {
            $author_id = $id['author_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM t_authors WHERE id = {$author_id};");
            $returned_author = $result->fetchAll(PDO::FETCH_ASSOC);

            $author_name = $returned_author[0]['author_name'];
            $id = $returned_author[0]['id'];
            $new_author = new Author($author_name, $id);
            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function getAll()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT * FROM t_books;");
        $books = array();
        foreach($returned_books as $book) {
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_books;");
    }


}
?>
