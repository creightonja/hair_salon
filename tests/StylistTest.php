<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Stylist.php";
    require_once "src/Client.php";

    $server = 'mysql:host=localhost:8080;dbname=hair_salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StylistTest extends PHPUnit_Framework_TestCase {

        protected function tearDown()
        {
            Stylist::deleteAll();
            //Client::deleteAll();
        }


        //Testing get Name function
        function test_getName()
        {
            //Arrange
            $stylist_name = "Ashley";
            $test_stylist = new Stylist($stylist_name);

            //Act
            $result = $test_stylist->getStylistName();

            //Assert
            $this->assertEquals($stylist_name, $result);

        }

        //Testing get ID function
        function test_getId()
        {
            //Arrange
            $stylist_name = "Ashley";
            $id = 1;
            $test_stylist = new Stylist($stylist_name, $id);

            //Act
            $result = $test_stylist->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        //Testing save function to database and getAll function
        function test_save()
        {
            //Arrange
            $stylist_name = "Ashley";
            $test_stylist = new Stylist($stylist_name);
            $test_stylist->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals($test_stylist, $result[0]);

        }

        //Testing multiple get all function
        function test_getAll()
        {
            //Arrange
            $stylist_name = "Ashley";
            $stylist_name2 = "Saki";
            $test_stylist = new Stylist($stylist_name);
            $test_stylist->save();
            $test_stylist2 = new Stylist($stylist_name2);
            $test_stylist2->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([$test_stylist, $test_stylist2], $result);
        }

        //Testing deleteAll function
        function test_deleteAll()
        {
            //Arrange
            $stylist_name = "Ashley";
            $stylist_name2 = "Saki";
            $test_stylist = new Stylist($stylist_name);
            $test_stylist->save();
            $test_stylist2 = new Stylist($stylist_name2);
            $test_stylist2->save();

            //Act
            Stylist::deleteAll();
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([], $result);

        }

        //Testing finder function
        function test_find()
        {
            //Arrange
            $stylist_name = "Ashley";
            $stylist_name2 = "Saki";
            $test_stylist = new Stylist($stylist_name);
            $test_stylist->save();
            $test_stylist2 = new Stylist($stylist_name2);
            $test_stylist2->save();

            //Act
            $result = Stylist::find($test_stylist->getId());

            //Assert
            $this->assertEquals($test_stylist, $result);
        }

        //Testing update function
        function testUpdate()
        {
            //Arrange
            $stylist_name = "Ashley";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $new_stylist_name = "Saki";

            //Act
            $test_stylist->update($new_stylist_name);

            //Assert
            $this->assertEquals($new_stylist_name, $test_stylist->getStylistName());
        }

        //Testing deleteOne function for deleting one entry
        function testDelete() {
            //Arrange
            $stylist_name = "Ashley";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $stylist_name2 = "Saki";
            $test_stylist2 = new Stylist($stylist_name2, $id);
            $test_stylist2->save();

            //Act
            $test_stylist->deleteOne();

            //Assert
            $this->assertEquals([$test_stylist2], Stylist::getAll());
        }

    }//End Class
?>
