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

        function testGetAuthorName()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $test_author = new Author($author_name);

            //Act
            $result = $test_author->getAuthorName();

            //Assert
            $this->assertEquals($author_name, $result);
        }

        function testSetAuthorName()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $test_author = new Author($author_name);

            //Act
            $test_author->setAuthorName("John Steinbeck");
            $result = $test_author->getAuthorName();

            //Assert
            $this->assertEquals("John Steinbeck", $result);
        }

        function testGetId()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $id = 1;
            $test_author = new Author($author_name, $id);

            //Act
            $result = $test_author->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $id = 1;
            $test_author = new Author($author_name, $id);

            //Act
            $test_author->save();

            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $id = 1;
            $test_author = new Author($author_name, $id);
            $test_author->save();

            $author_name2 = "John Steinbeck";
            $id2 = 2;
            $test_author2 = new Author($author_name2, $id2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }


        function testDeleteAll()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $id = 1;
            $test_author = new Author($author_name, $id);
            $test_author->save();

            $author_name2 = "John Steinbeck";
            $id2 = 2;
            $test_author2 = new Author($author_name2, $id2);
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
            $author_name = "J.K. Rowling";
            $test_author = new Author($author_name);
            $test_author->save();

            $new_author_name = "Harry Potter";

            //Act
            $test_author->update($new_author_name);

            //Assert
            $this->assertEquals("Harry Potter", $test_author->getAuthorName());
        }

        function testDeleteOne()
        {
            //Arrange
            $author_name = "J.K. Rowling";
            $id = 1;
            $test_author = new Author($author_name, $id);
            $test_author->save();

            $author_name2 = "John Steinbeck";
            $id2 = 2;
            $test_author2 = new Author($author_name2, $id2);
            $test_author2->save();

            //Act
            $test_author->deleteOne();

            //Assert
            $this->assertEquals([$test_author2], Author::getAll());
        }

        ///////////
        ///////////////All tests pass up to this point. Recieving errors for tests below.
        //////////
        function testAddBook()
        {
            //Arrange
            $author_name = "John Steinbeck";
            $test_author = new Author($author_name);
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
            $author_name = "John Steinbeck";
            $test_author = new Author($author_name);
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
    }
 ?>
