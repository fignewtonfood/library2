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
        // var_dump($author_ids);

        $authors = array();
        foreach ($author_ids as $id) {
            $author_id = $id['author_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM t_authors WHERE id = {$author_id};");
            $returned_author = $result->fetchAll(PDO::FETCH_ASSOC);
            $author_first = $returned_author[0]['author_first'];
            $author_last = $returned_author[0]['author_last'];
            $id = $returned_author[0]['id'];
            $new_author = new Author($author_first, $author_last, $id);
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

    static function find($search_id)
    {
        $found_book = null;
        $books = Book::getAll();
        foreach($books as $book) {
            $book_id = $book->getId();
            if ($book_id == $search_id) {
              $found_book = $book;
            }
        }
        return $found_book;
    }

    static function searchByTitle($search_string)
    {

        $clean_search_string = preg_replace('/[^A-Za-z0-9\s]/', '', $search_string);
        $lower_clean_search_string = strtolower($clean_search_string);
        $exploded_lower_clean_search_string = explode(' ', $lower_clean_search_string);
        $books = Book::getAll();
        $matches = array();
        foreach ($exploded_lower_clean_search_string as $word) {
            foreach ($books as $book) {
                $title = $book->getTitle();
                $clean_title = preg_replace('/[^A-Za-z0-9\s]/', '', $title);
                $lower_clean_title = strtolower($clean_title);
                $explode_lower_clean_title = explode(' ', $lower_clean_title);
                foreach ($explode_lower_clean_title as $title_pieces) {
                    if($word == $title_pieces) {
                        // $book_auth = $book->getAuthor();
                        array_push($matches, $book);
                    }
                }
            }
        }
        return $matches;
    }
}
?>
