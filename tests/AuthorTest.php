<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Book.php';
    require_once 'src/Author.php';
    require_once 'src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library_catalog_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            Patron::deleteAll();
        }

        function testGetAuthorFirst()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);

            //Act
            $result = $test_author->getAuthorFirst();

            //Assert
            $this->assertEquals($author_first, $result);
        }

        function testGetAuthorLast()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);

            //Act
            $result = $test_author->getAuthorLast();

            //Assert
            $this->assertEquals($author_last, $result);
        }


        function testSetAuthorFirst()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);

            //Act
            $test_author->setAuthorFirst("John");
            $result = $test_author->getAuthorFirst();

            //Assert
            $this->assertEquals("John", $result);
        }

        function testSetAuthorLast()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);

            //Act
            $test_author->setAuthorLast("John");
            $result = $test_author->getAuthorLast();

            //Assert
            $this->assertEquals("John", $result);
        }

        function testGetId()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            //Act
            $result = $test_author->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            //Act
            $test_author->save();

            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }


        function testDeleteAll()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            //Act
            Author::deleteAll();

            //Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $new_author_first = "Jack";
            $new_author_last = "John";

            //Act
            $test_author->update($new_author_first, $new_author_last);

            //Assert
            $this->assertEquals(["Jack", "John"], [$test_author->getAuthorFirst(), $test_author->getAuthorLast()]);
        }

        function testDeleteOne()
        {
            //Arrange
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "John";
            $author_last2 = "Steinbeck";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            //Act
            $test_author->deleteOne();

            //Assert
            $this->assertEquals([$test_author2], Author::getAll());
        }

        function testAddBook()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);

            //Assert
            $this->assertEquals($test_author->getBook(), [$test_book]);

        }

        function testGetBook()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Cannery Row";
            $test_book2 = new Book($title2);
            $test_book2->save();


            //Act
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);

            $result = $test_author->getBook();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function testFind()
        {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Cannery Row";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $result = Author::find($test_author->getId());

            //Assert
            $this->assertEquals($test_author, $result);
        }

        function testSearchByAuthorLast() {
            //Arrange
            $author_first = "J.K.";
            $author_last = "Rowling";
            $test_author = new Author($author_first, $author_last);
            $test_author->save();

            $author_first2 = "Stephen";
            $author_last2 = "King";
            $test_author2 = new Author($author_first2, $author_last2);
            $test_author2->save();

            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();
            $test_author->addBook($test_book);

            $title2 = "Harry Potter";
            $test_book2 = new Book($title2);
            $test_book2->save();
            $test_author->addBook($test_book2);

            $title3 = "Misery";
            $test_book3 = new Book($title3);
            $test_book3->save();
            $test_author2->addBook($test_book3);
            $search_string = "Rowling";

            //Act
            $result = Author::searchByAuthorLast($search_string);

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result[0]);
        }
    }
 ?>
