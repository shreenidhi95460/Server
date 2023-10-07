<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";

// Create a connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$database = $_GET['Database'];
$pgTable =  $_GET['PG_TABLE'];
$columns =  $_GET['COLUMNS'];
$names   =  $_GET['NAMES'];
$data    =  $_GET['DATA'];

// SQL query to create a new database
$sql = "CREATE DATABASE $database";

if ($conn->query($sql) === TRUE)
{
    echo "Database $database created successfully.";
    
    $conn = new mysqli($servername, $username, $password,$database);

    $pgTable = $conn->real_escape_string($pgTable);
    $columns = intval($columns);
    $columnNames = explode("_", $names);

    $sql = "CREATE TABLE $pgTable (";

    for ($i = 0; $i < $columns; $i++) 
    {
        $columnName = $conn->real_escape_string($columnNames[$i]);
        $sql .= "`$columnName` VARCHAR(255)";
        
        if ($i < $columns - 1) {
            $sql .= ",";
        }
    }

    $sql .= ");";

    if ($conn->query($sql) === TRUE) {
        echo "Table $pgTable created successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
} 
else
{
    $conn = new mysqli($servername, $username, $password,$database);
    $pgTable = $conn->real_escape_string($pgTable);
    $columns = intval($columns);
    $columnNames = explode("_", $names);

    $sql = "CREATE TABLE $pgTable (";

    for ($i = 0; $i < $columns; $i++) 
    {
        $columnName = $conn->real_escape_string($columnNames[$i]);
        $sql .= "`$columnName` VARCHAR(255)";
        
        if ($i < $columns - 1) 
        {
            $sql .= ",";
        }
    }

    $sql .= ");";

    if ($conn->query($sql) === TRUE) 
    {
        echo "Table $pgTable created successfully.";
    }
    else 
    {
        $columnNames = explode("_", $names);
        $columnData  = explode("_", $data);

        for ($i = 0; $i < $columns; $i++) 
        {
            $columnName = $conn->real_escape_string($columnNames[$i]);
            $columnData = $conn->real_escape_string($columnData[$i]);
        }
    }
}



// Close the connection
$conn->close();
?>