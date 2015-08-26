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

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
            Patron::deleteAll();
        }

        function testGetPatronName()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $test_patron = new Patron($patron_name, $phone_number);

            //Act
            $result = $test_patron->getPatronName();

            //Assert
            $this->assertEquals($patron_name, $result);
        }

        function testSetPatronName()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $test_patron = new Patron($patron_name, $phone_number);

            //Act
            $test_patron->setPatronName("Joe");
            $result = $test_patron->getPatronName();

            //Assert
            $this->assertEquals("Joe", $result);
        }

        function testGetId()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $id = 1;
            $test_patron = new Patron($patron_name, $phone_number, $id);

            //Act
            $result = $test_patron->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $id = 1;
            $test_patron = new Patron($patron_name, $phone_number, $id);
            // var_dump($test_patron);

            //Act
            $test_patron->save();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals($test_patron, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $id = 1;
            $test_patron = new Patron($patron_name, $phone_number, $id);
            $test_patron->save();

            $patron_name2 = "Joe";
            $phone_number2 = "0987654321";
            $id2 = 2;
            $test_patron2 = new Patron($patron_name2, $phone_number2, $id2);
            $test_patron2->save();

            //Act
            $result = Patron::getAll();
            var_dump($result);

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);
        }


        function testDeleteAll()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $id = 1;
            $test_patron = new Patron($patron_name, $phone_number, $id);
            $test_patron->save();

            $patron_name2 = "Joe";
            $phone_number2 = "0987654321";
            $id2 = 2;
            $test_patron2 = new Patron($patron_name2, $phone_number2, $id2);
            $test_patron2->save();

            //Act
            Patron::deleteAll();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals([], $result);
        }

        function testUpdate($column_to_update, $new_patron_information)
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $test_patron = new Patron($patron_name, $phone_number);
            $test_patron->save();

            $column_to_update = "patron_name";
            $new_patron_information = "Harry Potter";

            //Act
            $test_patron->update($new_patron_name);

            //Assert
            $this->assertEquals("Harry Potter", $test_patron->getPatronName());
        }

        function testDeleteOne()
        {
            //Arrange
            $patron_name = "Sally";
            $phone_number = "1234567890";
            $test_patron = new Patron($patron_name, $phone_number);
            $test_patron->save();

            $patron_name2 = "Joe";
            $phone_number2 = "0987654321";
            $test_patron2 = new Patron($patron_name2, $phone_number2);
            $test_patron2->save();

            //Act
            $test_patron->deleteOne();

            //Assert
            $this->assertEquals([$test_patron2], Patron::getAll());
        }
    }
 ?>
