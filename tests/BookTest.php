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

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            Patron::deleteAll();
        }

        function testGetTitle()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $test_book = new Book($title);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }

        function testSetTitle()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $test_book = new Book($title);

            //Act
            $test_book->setTitle("Cannary Row");
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals("Cannary Row", $result);
        }

        function testGetId()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Cannery Row";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }


        function testDeleteAll()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Cannery Row";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            Book::deleteAll();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function testUpdate()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $test_book = new Book($title);
            $test_book->save();

            $new_title = "Harry Potter";

            //Act
            $test_book->update($new_title);

            //Assert
            $this->assertEquals("Harry Potter", $test_book->getTitle());
        }

        function testDeleteOne()
        {
            //Arrange
            $title = "Grapes of Wrath";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Cannery Row";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            //Act
            $test_book->deleteOne();

            //Assert
            $this->assertEquals([$test_book2], Book::getAll());
        }
    }
 ?>
