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

    ///////////
    ///////////////All tests pass up to this point. Recieving errors for tests pertaining to functions commented-out below.
    //////////

    // function addBook($book)
    // {
    //     $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$book->getId()};)");
    // }
    //
    // function getBook()
    // {
    //     $query = $GLOBALS['DB']->query("SELECT book_id FROM authors_books WHERE author_id = {$this->getId()};");
    //     $book_ids = $query->fetchAll(PDO::FETCH_ASSOC);
    //
    //     $books = array();
    //     foreach ($book_ids as $id) {
    //         $book_id = $id['book_id'];
    //         $result = $GLOBALS['DB']->query("SELECT * FROM t_books WHERE id = {$book_id};");
    //         $returned_book = $result->fetchAll(PDO::FETCH_ASSOC);
    //
    //         $title = $returned_book[0]['title'];
    //         $id = $returned_book[0]['id'];
    //         $new_book = new Book($title, $id);
    //         array_push($books, $new_book);
    //     }
    //     return $books;
    // }

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
