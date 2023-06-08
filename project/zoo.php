<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="zoo.css">
    <title>Paradise Zoo Manage System</title>
</head>
    <center>
    <h1>&#128011; Paradise Zoo Manage System &#128011;</h1>
        </center>


<hr>
<h2>Selection on tables</h2>
<form method="GET" action="result.php"> <!--refresh page when submitted-->
    <select name="property" id="property">
        <option value="customer"> Customer </option>
        <option value="employee">Employee</option>
    </select>
<!--    <label for="property">Column</label>-->
<!--    date: <input type="date"  name="date">-->
    <input type="hidden" id="selectionRequest" name="selectionRequest">
    <input type="submit" name="selection">
</form>

<hr>
<h2>Projection on Animal_info table</h2>
<form method="GET" action="result.php">
    <input type='checkbox' id='animal_ID' name='animal_ID' value='animal_ID'>
        <label for='animal_ID'> Animal ID </label><br>
    <input type='checkbox' id='animal_name' name='animal_name' value='animal_name'>
        <label for='animal_name'> Name </label><br>
    <input type='checkbox' id='age' name='age' value='age'>
        <label for='age'> Age </label><br>
    <input type='checkbox' id='species' name='species' value='species'>
        <label for='species'> Species </label><br>
    <input type='checkbox' id='employee_ID' name='employee_ID' value='employee_ID'>
        <label for='employee_ID'> Breeder ID </label><br>
    <input type='checkbox' id='space_ID' name='space_ID' value='space_ID'>
        <label for='space_ID'> Living Space ID </label><br>
    <input type='checkbox' id='district_ID' name='district_ID' value='district_ID'>
        <label for='district_ID'> District ID </label><br>
    <input type="hidden" id="projectionRequest" name="projectionRequest">
    <input type="submit" name="projection">
</form>

<hr />
<h2>Join on Show table for theater name and district name</h2>
<form method="GET" action="result.php">
    <label for="property">Column</label>
    date: <input type="date"  name="date">
    <input type="hidden" id="joinRequest" name="joinRequest">
    <input type="submit" name="join">
</form>

<hr />
<h2> Insertion on Employee table</h2>
<form method="POST" action="result.php">
    <input type="hidden" id="insertRequest" name="insertRequest">
    ID: <input type="number" name="employee_ID"> <br /><br />
    name: <input type="text" name="employee_name"> <br /><br />
    salary: <input type="number" name="salary"> <br /><br />
    start date: <input type="date"  name="start_date"> <br /><br />
    mentor ID:  <input type="number" name="mentor_ID"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit">
</form>

<hr />
<h2>Update breeder status in Breeder table </h2>
<p> PLEASE double check the employee ID is CORRECT.</p>

<form method="POST" action="result.php"> <!--refresh page when submitted-->
    <input type="hidden" id="updateRequest" name="updateRequest">
    employee ID: <input type="number" name="id"> <br /><br />
    change status: <select name="choice" id="updateChoice">
        <option value="resigned"> resigned </option>
        <option value="on-the-job"> on-the-job </option>
    </select>
    <input type="submit" value="Update" name="updateSubmit"></p>
</form>

<hr />
<h2>Delete Animal </h2>
<p> !!!Making sure you delete the correct animal ID!!!</p>

<?php
require_once "result.php";
global $db_conn;
connectToDB();
$selectCustomer = "select s.animal_ID,s.animal_name, s.species, s.age from animal_info s;";
$result = $db_conn->query($selectCustomer);


//echo "<br>Retrieved data from table Customer:<br>";
echo "<table>";
echo "<tr><th>ID</th><th>Aimal Name</th><th>Species</th><th>Age</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["animal_ID"] . "</td><td>" . $row["animal_name"] . "</td><td>" . $row["species"] . "</td><td>" . $row["age"] . "</td></tr>";
}
echo "</table>";
echo"<br />";
echo"<br />";
disconnectFromDB();
?>

<form method="POST" action="result.php"> <!--refresh page when submitted-->
    <input type="hidden" id="deleteRequest" name="deleteRequest">
    Animal ID: <input type="number" name="animal_ID"> <br /><br />
    </select>
    <input type="submit" value="Delete" name="deleteSubmit">
</form>




<hr />
<h2>Count the number of animals</h2>
<form method="GET" action="result.php"> <!--refresh page when submitted-->
    <input type="hidden" id="countRequest" name="countRequest">
    <select name="species_choice">
        <option value="Bactrianus"> Bactrianus </option>
        <option value="Brachyurus"> Brachyurus </option>
        <option value="Delphinus truncatus"> Delphinus truncatus </option>
        <option value="Eastern Mole"> Eastern Mole </option>
        <option value="Forsteri"> Forsteri </option>
    </select>
    <input type="submit" name="countSubmit"></p>
</form>

<hr />
<h2>Which animal sees the doctor the most in each living space</h2>
<form method="GET" action="result.php"> <!--refresh page when submitted-->
    <input type="hidden" id="sickRequest" name="sickRequest">
    <select name="Living Space">
        <option value="Bactrain Camel"> Bactrain Camel </option>
        <option value="Dophins"> Dophins </option>
        <option value="Maned Wolf"> Maned Wolf </option>
        <option value="Penguin"> Penguin </option>
        <option value="Mole"> Mole </option>
    </select>
    <input type="submit" name="sickSubmit"></p>
</form>

<hr />
<h2>Profit check for animal shows</h2>
<p>Here is the place to find out shows have profit greater/less than average</p>
<form method="GET" action="result.php">
    <input type="hidden" id="profitRequest" name="profitRequest">
    <select name="greaterorless">
        <option value="greater"> greater than average</option>
        <option value="less"> less than average </option>
    </select>
    <input type="submit" name="profitSubmit"></p>
</form>

<hr />
<h2>Animal(s) that See ALL the Vets</h2>
<?php
require_once "result.php";
global $db_conn;
connectToDB();
$select_ani = "select a.animal_ID, a.animal_name, a.age, a.species
from animal_info a
where not exists
    (select s.employee_ID
    from Specialist s
    where s.s_status = 'on-the-job'
    and not exists
    (select m.employee_ID
     from medical_record m
     where s.employee_ID = m.employee_ID and
           m.animal_ID = a.animal_ID))";


$result = $db_conn->query($select_ani);


//echo "<br>Retrieved data from table Customer:<br>";
echo "<table>";
echo "<tr><th>Animal ID</th><th>Animal Name</th><th>Animal Age</th><th>Animal Species</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["animal_ID"] . "</td><td>" . $row["animal_name"] . "</td><td>" . $row["age"] .
        "</td><td>" . $row["species"] ."</td></tr>";
}
echo "</table>";
echo"<br />";
echo"<br />";
disconnectFromDB();
?>
</form>


</body>
</html>