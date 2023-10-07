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
$sql = "CREATE DATABASE IF NOT EXISTS $database";

if ($conn->query($sql) === TRUE)
{
    echo "Database $database created successfully.";
}

// Close the connection
$conn->close();

// Create a connection to the newly created database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

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

if ($conn->query($sql) === TRUE) 
{
    echo "Table $pgTable created successfully.";
}
else 
{
    echo "Error creating table: " . $conn->error;
}

// Insert data into the table
$columndata = explode("_", $data);

$sql = "INSERT INTO $pgTable (";

for ($i = 0; $i < $columns; $i++) 
{
    $data = $conn->real_escape_string($columndata[$i]);
    $sql .= "`$columnNames[$i]`";
    
    if ($i < $columns - 1) 
    {
        $sql .= ",";
    }
}    

$sql .= ") VALUES (";

for ($i = 0; $i < $columns; $i++) 
{
    $data = $conn->real_escape_string($columndata[$i]);
    $sql .= "'$data'";
    
    if ($i < $columns - 1) 
    {
        $sql .= ",";
    }
}

$sql .= ");";

if ($conn->query($sql) === TRUE) 
{
    echo "Data inserted into $pgTable successfully.";
}
else 
{
    echo "Error inserting data: " . $conn->error;
}

// Close the connection
$conn->close();
?>
