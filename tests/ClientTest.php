<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    //Linking class for testing
    require_once "src/Client.php";

    //Setting to MAMP server localhost with root pw and user
    $server = 'mysql:host=localhost:8080;dbname=hair_salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class ClientTest extends PHPUnit_Framework_TestCase {

        //Clears data for next test after each test
        protected function tearDown()
        {
            Client::deleteAll();
        }

        function test_getId() {
            //Arrange
            $stylist_name = "Saki";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();


            $client_name = "Alicia";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            //Act
            $result = $test_client->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_getStylistId() {
            //Arrange
            $stylist_name = "Saki";
            $id = null;
            $test_stylist = new Stylist($stylist_name, $id);
            $test_stylist->save();

            $client_name = "Alicia";
            $stylist_id = $test_stylist->getId();
            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            //Act
            $result = $test_client->getstylistId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

    }//End class
?>
