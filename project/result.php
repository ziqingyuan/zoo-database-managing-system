<!--The structure of this project is inspired by "Test Oracle file for UBC CPSC304 2018 Winter Term 1" -->

<html>
<head>
    <link rel="stylesheet" href="zoo.css">
    <title>Paradise Zoo Manage System</title>
</head>
<body>

<?php

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())


function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}


function connectToDB() {
    global $db_conn;
    $servername = "servername";
    $username = "username";
    $password = "password";
    $dbname = "dbname";

    // Create connection
    $db_conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($db_conn->connect_error) {
//        die("Connection failed: " . $db_conn->connect_error);
        debugAlertMessage("Cannot connect to Database");
        $e = ysqli_connect_error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    } else {
        debugAlertMessage("Database is Connected");
        return true;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Databases");
    $db_conn->close();;
}


function handleSelectionRequest() {
    if ($_GET['property'] == 'customer') {
        echo "<form method='GET'>
                     ____Customer table loading____<br>
                        select projection column(s) <br>
                <input type='checkbox' id='customer_ID' name='customer_ID' value='customer_ID'>
                <label for='customer_ID'> Customer ID </label><br>
                <input type='checkbox' id='customer_name' name='customer_name' value='customer_name'>
                <label for='customer_name'> Name </label><br>
                <input type='checkbox' id='customer_age' name='customer_age' value='customer_age'>
                <label for='customer_age'> Age </label><br>
                <input type='checkbox' id='phone_num' name='phone_num' value='phone_num'>
                <label for='phone_num'> Phone Number </label><br>
                <input type='checkbox' id='address' name='address' value='address'>
                <label for='address'> Address </label><br>
                <label for='age'> Greater Than  </label>
                <input type='number' id='age' name='age' value='age'>
                years old         <br>
        <input type='hidden' id='selectionCustomerRequest' name='selectionCustomerRequest'>
        <input type='submit' name='selectionCustomerRequest'>
                </form>";
    } else {
        echo "<form method='GET'>
                     Employee table loading____ <br>
                        select projection column(s) <br>
                <input type='checkbox' id='employee_ID' name='employee_ID' value='employee_ID'>
                <label for='employee_ID'> Employee ID </label><br>
                <input type='checkbox' id='employee_name' name='employee_name' value='employee_name'>
                <label for='employee_name'> Name </label><br>
                <input type='checkbox' id='salary' name='salary' value='salary'>
                <label for='salary'> Salary </label><br>
                <input type='checkbox' id='start_date' name='start_date' value='start_date'>
                <label for='start_date'> Start Date </label><br>
                <input type='checkbox' id='mentor_id' name='mentor_id' value='mentor_id'>
                <label for='mentor_id'> Mentor ID </label><br>
                <input type='checkbox' id='status' name='status' value='status'>
                <label for='status'> Status </label><br>
                <select name='status_val' id='status_val'>
                    <option value='all'> all </option>
                    <option value='resigned'> resigned </option>
                    <option value='on-the-job'> on-the-job </option>
                </select>
        <input type='hidden' id='selectionEmployeeRequest' name='selectionEmployeeRequest'>
        <input type='submit' name='selectionEmployeeRequest'>
                </form>";
    }
}
function handleSelectionCustomerRequest() {
    global $db_conn;

    $tuple = array ();
    if (isset($_GET['customer_ID'])) {
        array_push($tuple, $_GET['customer_ID']);
    }
    if (isset($_GET['customer_name'])) {
        array_push($tuple, $_GET['customer_name']);
    }
    if (isset($_GET['customer_age'])) {
        array_push($tuple, $_GET['customer_age']);
    }
    if (isset($_GET['phone_num'])) {
        array_push($tuple, $_GET['phone_num']);
    }
    if (isset($_GET['address'])) {
        array_push($tuple, $_GET['address']);
    }

    if (count($tuple) > 0) {
        $column = "";
        $row_title = "";
        for($i = 0;$i < count($tuple);$i++) {
            $column .= $tuple[$i];
            $row_title .= "<td> $tuple[$i] </td>";
            if ($i != count($tuple) - 1) {
                $column .= ",";
                //$row_title .= "</tr>";
            }
        }
        $sql = "select $column from Customer";


        $age = 0;
        if (isset($_GET['age']) && $_GET['age'] != '') {
            $age = $_GET['age'];
            $sql .= " where customer_age > $age";
        }
        $result = $db_conn->query($sql);

        echo "<table class='db-table'>";
        echo $row_title;

        while ($row = $result->fetch_assoc()) {
            $each_row = "<tr>";
            for($i = 0;$i < count($tuple);$i++) {
                $each_row .= "<td>" . $row[$tuple[$i]] . "</td>";
                if ($i != count($tuple) - 1) {
                    $column .= ",";
                    $row_title .= "</tr>";
                }
            }
            echo $each_row;
            //echo "<tr><td>" . $row["show_ID"] . "</td><td>" . $row["show_name"] . "</td><td>" . $row["schedule_time"] . "</td><td>" . $row["theater_name"] . "</td><td>" . $row["district_name"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Please select a column";
    }


}

function handleSelectionEmployeeRequest() {
    global $db_conn;

    $tuple = array ();
    if (isset($_GET['employee_ID'])) {
        array_push($tuple, $_GET['employee_ID']);
    }
    if (isset($_GET['employee_name'])) {
        array_push($tuple, $_GET['employee_name']);
    }
    if (isset($_GET['salary'])) {
        array_push($tuple, $_GET['salary']);
    }
    if (isset($_GET['start_date'])) {
        array_push($tuple, $_GET['start_date']);
    }
    if (isset($_GET['mentor_id'])) {
        array_push($tuple, $_GET['mentor_id']);
    }
    if (isset($_GET['status'])) {
        array_push($tuple, $_GET['status']);
    }

    if (count($tuple) > 0) {
        $column = "";
        $row_title = "";
        for($i = 0;$i < count($tuple);$i++) {
            $column .= $tuple[$i];
            $row_title .= "<td> $tuple[$i] </td>";
            if ($i != count($tuple) - 1) {
                $column .= ",";
                //$row_title .= "</tr>";
            }
        }
        $sql = "select $column from Employee";

        $status_val = $_GET['status_val'];
        if ($status_val != "all") {
            $sql .= " where status  = '$status_val'";
        }
        $result = $db_conn->query($sql);

        echo "<table class='db-table'>";
        echo $row_title;

        while ($row = $result->fetch_assoc()) {
            $each_row = "<tr>";
            for($i = 0;$i < count($tuple);$i++) {
                $each_row .= "<td>" . $row[$tuple[$i]] . "</td>";
                if ($i != count($tuple) - 1) {
                    $column .= ",";
                    $row_title .= "</tr>";
                }
            }
            echo $each_row;
            //echo "<tr><td>" . $row["show_ID"] . "</td><td>" . $row["show_name"] . "</td><td>" . $row["schedule_time"] . "</td><td>" . $row["theater_name"] . "</td><td>" . $row["district_name"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Please select a column";
    }


}

function handleJoinRequest() {
    global $db_conn;

    $date = $_GET['date'];
//    echo "$col";
    $sql = "select s.show_ID, s.show_name, s.schedule_time, t.theater_name, d.district_name from show_info s
    left outer join show_fee sf on s.show_name = sf.show_name
    left outer join Theater t on s.theater_ID = t.theater_ID
    left outer join District d on t.district_ID = d.district_ID
    where '$date' = date(s.schedule_time)
;";
//    echo "$sql";
    $result = $db_conn->query($sql);

//    echo "<br>Retrieved data from table Customer:<br>";
    echo "<table class='db-table'>";
    echo "<tr><th>show_id</th><th>show_name</th><th>schedule_time</th><th>theater_name</th><th>district_name</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["show_ID"] . "</td><td>" . $row["show_name"] . "</td><td>" . $row["schedule_time"] . "</td><td>" . $row["theater_name"] . "</td><td>" . $row["district_name"] . "</td></tr>";
    }
    echo "</table>";
}

function handleProjectionRequest() {
    global $db_conn;

    $tuple = array ();
    if (isset($_GET['animal_ID'])) {
        array_push($tuple, $_GET['animal_ID']);
    }
    if (isset($_GET['animal_name'])) {
        array_push($tuple, $_GET['animal_name']);
    }
    if (isset($_GET['age'])) {
        array_push($tuple, $_GET['age']);
    }
    if (isset($_GET['species'])) {
        array_push($tuple, $_GET['species']);
    }
    if (isset($_GET['employee_ID'])) {
        array_push($tuple, $_GET['employee_ID']);
    }
    if (isset($_GET['space_ID'])) {
        array_push($tuple, $_GET['space_ID']);
    }
    if (isset($_GET['district_ID'])) {
        array_push($tuple, $_GET['district_ID']);
    }

    if (count($tuple) > 0) {
        $column = "";
        $row_title = "";
        for($i = 0;$i < count($tuple);$i++) {
            $column .= $tuple[$i];
            $row_title .= "<td> $tuple[$i] </td>";
            if ($i != count($tuple) - 1) {
                $column .= ",";
                //$row_title .= "</tr>";
            }
        }
        $sql = "select $column from animal_info";

        $result = $db_conn->query($sql);

        echo "<table class='db-table'>";
        echo $row_title;

        while ($row = $result->fetch_assoc()) {
            $each_row = "<tr>";
            for($i = 0;$i < count($tuple);$i++) {
                $each_row .= "<td>" . $row[$tuple[$i]] . "</td>";
                if ($i != count($tuple) - 1) {
                    $column .= ",";
                    $row_title .= "</tr>";
                }
            }
            echo $each_row;
            //echo "<tr><td>" . $row["show_ID"] . "</td><td>" . $row["show_name"] . "</td><td>" . $row["schedule_time"] . "</td><td>" . $row["theater_name"] . "</td><td>" . $row["district_name"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Please select a column";
    }

}


// insert: created by Chris - not test yet
function handleInsertRequest() {
    global $db_conn;


    $id = $_POST['employee_ID'];
    $name = $_POST['employee_name'];
    $salary = $_POST['salary'];
    $start_date = $_POST['start_date'];
    $mentor_id = $_POST['mentor_ID'];
    $status = "on-the-job";

    $sql = "insert into Employee (employee_ID, employee_name, salary, start_date, mentor_ID, status)
                values($id,'$name',$salary,'$start_date',$mentor_id,'$status');";
    echo $sql;

    if (mysqli_query($db_conn, $sql)) {
        echo "add employee sucessfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db_conn);
    }
}

// Update: Specialist, breeder, zoo_keeper
function handleUpdateRequest() {
    global $db_conn;

    $id = $_POST['id'];
    $new_status = $_POST['choice'];

    $sql ="UPDATE breeder SET  b_status = '$new_status' WHERE employee_ID = $id";

    //$update = $db_conn->query($sql);
    if(mysqli_query($db_conn,$sql))
    {
        echo "update success<br>";
    }
    else
    {
        echo "FAIL <br>";
    }

//    executePlainSQL("UPDATE breeder SET b_status='" . $new_status . "' WHERE employee_ID='" . $id . "'");
//    OCICommit($db_conn);
}
// ****************************************

function handleDeleteRequest(){
    global $db_conn;

    $id = $_POST['animal_ID'];

    $sql ="DELETE FROM animal_info WHERE animal_ID = $id";

    //$update = $db_conn->query($sql);
    if(mysqli_query($db_conn,$sql))
    {
        echo "delete success<br>";
    }
    else
    {
        echo "FAIL <br>";
    }
}

# animal_info group_by species -> count  //min, max, avg age
function handleGroupByRequest()
{
    global $db_conn;
    $species = $_GET['species_choice'];
    $sql = "SELECT species,Count(*) AS ct FROM animal_info  where species = '$species'group by species";

    $result = $db_conn->query($sql);

//    echo "<br>Table:<br>";
//    echo "<table>";
//    while ($row = $result->fetch_assoc()) {
//        //echo "<tr><td>" . "species: ". $row['species'] . "count:" . $row['ct'] . "</td></tr>";
//        echo "<tr><td>" . $row . "</td></tr>";
//        echo "</table>";
//    }
    echo "<table class='db-table'>";
    echo "<tr><th>Species</th><th>Total animal count</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . "Species: ".$row['species'] ." </th><th> Total animal: ". $row['ct'] . "</td></tr>";
    }
    echo "</table>";


}

#Animal that went to the doctor the most in each living space
function handleSickRequest()
{
    global $db_conn;
    $space_name = $_GET['Living_Space'];

    $sql = "select max(Total) as num, animal_ID as aid, animal_name as anm
            from
            (select b.animal_ID, b.animal_name, l.space_name, Total
            from LivingSpace l
            right join(select m.animal_ID, a.animal_name, space_ID, Total
                        from animal_info a
                        right join (select count(*) as Total, animal_ID
                                    from medical_record
                                    group by animal_ID) m
                        on a.animal_ID = m.animal_ID) b
            on b.space_ID = l.space_ID) k
            group by space_name
            having space_name = '$space_name'";


    $result = $db_conn->query($sql);

    echo "<table class='db-table'>";
    echo "<tr><th>Num of Record</th><th>Animal ID</th><th>Animal Name</th></tr>";

    while ($row = $result->fetch_array()) {
        echo "<tr><th>" .$row['num']."</th><th>".$row['aid']."</th><th>".$row['anm'].  "</th></tr>";
    }
    echo "</table>";

}

//NESTED GROUP BY: which show's revenue is higher that avg revenue
function handleProfitRequest() {
    global $db_conn;
    $gol = $_GET['greaterorless'];

    if ($gol == 'greater') {
        $sql = "select show_name,price*tot/num as avg
from (select show_name, price,count(customer_ID) as tot, count(distinct show_ID) as num
from(
        select  si.show_id,si.show_name,sf.price,sp.customer_ID
        from show_info si
            left join show_fee sf
                on sf.show_name=si.show_name
            left join show_purchase sp
                on  si.show_ID = sp.show_ID
    ) as total
group by show_name
) as per
having avg > (
    select a/b
    from(
    select sum(price*tot/num) as a, sum(num) as b
    from (select show_name, price,count(customer_ID) as tot, count(distinct show_ID) as num
          from(
                  select  si.show_id,si.show_name,sf.price,sp.customer_ID
                  from show_info si
                           left join show_fee sf
                                     on sf.show_name=si.show_name
                           left join show_purchase sp
                                     on  si.show_ID = sp.show_ID
              ) as total
          group by show_name
         ) as per
    ) as subb
    );";
    } else {
        $sql = "select show_name,price*tot/num as avg
from (select show_name, price,count(customer_ID) as tot, count(distinct show_ID) as num
from(
        select  si.show_id,si.show_name,sf.price,sp.customer_ID
        from show_info si
            left join show_fee sf
                on sf.show_name=si.show_name
            left join show_purchase sp
                on  si.show_ID = sp.show_ID
    ) as total
group by show_name
) as per
having avg < (
    select a/b
    from(
    select sum(price*tot/num) as a, sum(num) as b
    from (select show_name, price,count(customer_ID) as tot, count(distinct show_ID) as num
          from(
                  select  si.show_id,si.show_name,sf.price,sp.customer_ID
                  from show_info si
                           left join show_fee sf
                                     on sf.show_name=si.show_name
                           left join show_purchase sp
                                     on  si.show_ID = sp.show_ID
              ) as total
          group by show_name
         ) as per
    ) as subb
    );";
    }

    $result = $db_conn->query($sql);

    echo "<table class='db-table'>";
    echo "<tr><th>Show name</th><th>Profit</th></tr>";

    while ($row = $result->fetch_array()) {
        echo "<tr><th>" .$row['show_name']."</th><th>".$row['avg']. "</th></tr>";
    }
    echo "</table>";

}

///////////////static showing //////////////////////
//start show table for customer
/*
connectToDB();
$selectCustomer = "select * from MyGuests";
$result = $db_conn->query($selectCustomer);
echo "<br>Retrieved data from table Customer:<br>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Registration date</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["id"] . "</td><td>" . $row["firstname"] . "</td><td>" . $row["lastname"] . "</td><td>" . $row["email"] . "</td><td>" . $row["reg_date"] . "</td></tr>";
}
echo "</table>";
/////////////////end showing//////////////////
*/
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('projection', $_GET)) {
            handleProjectionRequest();
        } else if (array_key_exists('selection', $_GET)) {
            handleSelectionRequest();
        } else if (array_key_exists('countRequest',$_GET)) {
            handleGroupByRequest();
        } else if (array_key_exists('sickRequest',$_GET)) {
            handleSickRequest();
        } else if (array_key_exists('profitRequest',$_GET)) {
            handleProfitRequest();
        } else if (array_key_exists('selectionCustomerRequest',$_GET)) {
            handleSelectionCustomerRequest();
        } else if (array_key_exists('join',$_GET)) {
            handleJoinRequest();
        } else if (array_key_exists('selectionEmployeeRequest',$_GET)) {
            handleSelectionEmployeeRequest();
        }
    }

    disconnectFromDB();
}


if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
    handlePOSTRequest();
} else if (isset($_GET['projectionRequest']) || isset($_GET['selectionRequest'] )|| isset($_GET['countRequest'])
    || isset($_GET['sickRequest'])|| isset($_GET['profitRequest']) || isset($_GET['selectionCustomerRequest']) || isset($_GET['joinRequest'])
    || isset($_GET['selectionEmployeeRequest'])) {
    handleGETRequest();
}


// Chris: idk ...
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('updateRequest', $_POST)) {
            handleUpdateRequest();
        } else if (array_key_exists('deleteRequest', $_POST)){
            handleDeleteRequest();
        } else if (array_key_exists('insertRequest', $_POST)) {
            handleInsertRequest();
        }

        disconnectFromDB();
    }
}


?>
</body>
</html>

