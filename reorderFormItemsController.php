<?php

namespace controller;

use Exception;
use PDO;
use PDOException;

class reorderFormItemsController {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "reorderformitems";

    private $conn;
    private $users;

    public function __construct() {
        $this->setConnection();

        $this->users = $this->getUsers();
    }

    private function setConnection() {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/reorderFormItems.php?message=danger.connection');
            die();
        }

        $this->conn =  $conn;
    }

    private function getUsers() {
        $stmt = $this->conn->prepare('SELECT * FROM users order by sortOrder;');
        $stmt->execute();
        
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function showUsersInTable() {
        $output = '';

        foreach ($this->users as $user) {
            $id = $user['id'];
            $first = $user['first'];
            $last = $user['last'];
            $sortOrder = $user['sortOrder'];

            $output .= <<<TableRows
            <tr class="ui-state-default bg-white">
                <td class="index">$id</td>
                <td>$first</td>
                <td>$last</td>
                <td>
                    <input
                        type="hidden"
                        form="reorderForm"
                        name="userId[]"
                        id="$id"
                        value="$id"
                        class="form-control"
                        required
                    >
                    <input
                        type="number"
                        form="reorderForm"
                        name="order[]"
                        id="$sortOrder"
                        value="$sortOrder"
                        class="form-control"
                        required
                    >
                </td>
            </tr>
TableRows;
        }

        return $output;
    }

    public function saveSortOrder($postData) {
        try {
            $newOrder = array();
            foreach ($postData['userId'] as $key=>$userId) {
                array_push($newOrder, htmlspecialchars($userId));
            }
        } catch (Exception $e) {
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/reorderFormItems.php?message=danger.validate');
            die();
        }


        try {
            $this->conn->beginTransaction();

            // Empty out all sortOrders because the order must be unique
            $stmt = $this->conn->prepare('UPDATE users SET sortOrder=null;');
            $stmt->execute();

            $i = 1;
            foreach ($newOrder as $key=>$userId) {
                $stmt = $this->conn->prepare('UPDATE users SET sortOrder=? WHERE id=?;');
                $stmt->execute(array(
                    (int)$i++,
                    (int)$userId
                ));
            }

            $this->conn->commit();

            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/reorderFormItems.php?message=success.saved');
            die();
        } catch (PDOException $e) {
            $this->conn->rollback();
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/reorderFormItems.php?message=danger.update');
            die();
        }

    }

    public function __destruct()
    {
        $this->users = null;
        $this->conn = null;
    }
}