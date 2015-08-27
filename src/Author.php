<?php
class Author
{
    private $author_first;
    private $author_last;
    private $id;

    function __construct($author_first, $author_last,  $id=null)
    {
        $this->author_first = $author_first;
        $this->author_last = $author_last;
        $this->id = $id;
    }

    function setAuthorFirst($new_author_first)
    {
        $this->author_first = (string) $new_author_first;
    }

    function setAuthorLast($new_author_last)
    {
        $this->author_last = (string) $new_author_last;
    }

    function getAuthorFirst()
    {
        return $this->author_first;
    }

    function getAuthorLast()
    {
        return $this->author_last;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO t_authors (author_first, author_last) VALUES ('{$this->getAuthorFirst()}', '{$this->getAuthorLast()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function update($new_author_first, $new_author_last)
    {
        $GLOBALS['DB']->exec("UPDATE t_authors SET author_first = '{$new_author_first}', author_last = '{new_author_last}' WHERE id = {$this->getId()};");
        $this->setAuthorFirst($new_author_first);
        $this->setAuthorLast($new_author_last);
    }

    function deleteOne()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors WHERE id = {$this->getId()};");
    }


    function addBook($book)
    {
        $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$book->getId()});");
    }

    function getBook()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT t_books.* FROM t_authors
            JOIN authors_books ON (t_authors.id = authors_books.author_id)
            JOIN t_books ON (authors_books.book_id = t_books.id)
            WHERE t_authors.id = {$this->getId()};");
            var_dump($returned_books);
        $books = array();
        foreach ($returned_books as $book) {
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }


    static function getAll()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT * FROM t_authors;");
        $authors = array();
        foreach($returned_authors as $author) {

            $author_first = $author['author_first'];
            $author_last = $author['author_last'];
            $id = $author['id'];
            $new_author = new Author($author_first, $author_last, $id);

            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM t_authors;");
    }


    static function find($search_id)
    {
        $found_author = null;
        $authors = Author::getAll();
        foreach($authors as $author) {
            $author_id = $author->getId();
            if ($author_id == $search_id) {
              $found_author = $author;
            }
        }
        return $found_author;
    }

    static function searchByAuthorLast($search_string)
    {
        $clean_search_string = preg_replace('/[^A-Za-z0-9\s]/', '', $search_string);
        $lower_clean_search_string = strtolower($clean_search_string);
        $authors = Author::getAll();
        $matches = array();
        foreach ($authors as $author) {
            $author_last = $author->getAuthorLast();
            $clean_author = preg_replace('/[^A-Za-z0-9\s]/', '', $author_last);
            $lower_clean_author = strtolower($clean_author);
            if($lower_clean_author == $lower_clean_search_string) {
                $book = $author->getBook();
                array_push($matches, $book);
            }
        }
        return $matches;
    }

}
?>
