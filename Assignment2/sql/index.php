<?php
$servername = "172.17.0.3";
$username = "root";
$password = "cs252-a2-mysql";
$dbname = "employees";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Web Interface For Employee Query</title>
    </head>
    <body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
?>
<?php
$sql = "";
if ($_REQUEST["_query"] == 1) {
    $sql = "SELECT A.emp_no AS 'Employee ID', A.last_name AS 'Last Name', B.dept_name AS 'Department' FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no JOIN departments B ON C.dept_no = B.dept_no WHERE 1 = 1";
    if ($_REQUEST["id"] == "" and $_REQUEST["lastname"] == "" and $_REQUEST["dept"] == "") {
        die("Invalid Query! All fields are empty");
    }
    if ($_REQUEST["id"] != "") {
        $sql = $sql . " AND A.emp_no = '" . $_REQUEST["id"] . "'";
    }
    if ($_REQUEST["lastname"] != "") {
        $sql = $sql . " AND A.last_name = '" . $_REQUEST["lastname"] . "'";
    }
    if ($_REQUEST["dept"] != "") {
        $sql = $sql . " AND B.dept_name = '" . $_REQUEST["dept"] . "'";
    }
} else if ($_REQUEST["_query"] == 2) {
    $sql = "SELECT COUNT(A.emp_no) AS 'Number of Employees', B.dept_name AS 'Department' FROM dept_emp A JOIN departments B ON A.dept_no = B.dept_no GROUP BY A.dept_no ORDER BY COUNT(A.emp_no)";
} else if ($_REQUEST["_query"] == 3) {
    $sql = "SELECT A.emp_no AS 'Employee ID', A.first_name AS 'First Name', A.last_name AS 'Last Name', A.hire_date AS 'Hire Date' FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no JOIN departments B ON C.dept_no = B.dept_no WHERE 1 = 1";
    if ($_REQUEST["dept"] == "") {
        die("Invalid Query! All fields are empty");
    }
    if ($_REQUEST["dept"] != "") {
        $sql = $sql . " AND B.dept_name = '" . $_REQUEST["dept"] . "'";
    }
    $sql = $sql . " ORDER BY A.hire_date";
} else if ($_REQUEST["_query"] == 4) {
    if ($_REQUEST["dept"] == "") {
        die("Invalid Query! All fields are empty");
    }
    $sql = "";
    $sql1 = "SELECT COUNT(*) AS ANS FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no  JOIN departments B on C.dept_no = B.dept_no WHERE 1 = '1' AND B.dept_name = '" . $_REQUEST["dept"] . "' AND A.gender = 'M'";
    $male = $conn->query($sql1)->fetch_assoc()["ANS"];
    $sql2 = "SELECT COUNT(*) AS ANS FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no  JOIN departments B on C.dept_no = B.dept_no WHERE 1 = '1' AND B.dept_name = '" . $_REQUEST["dept"] . "' AND A.gender = 'F'";
    $female = $conn->query($sql2)->fetch_assoc()["ANS"];
    echo 'Female to Male ratio: ' . ($female / $male) * 100 . '%';
} else if ($_REQUEST["_query"] == 5) {
    if ($_REQUEST["dept"] == "") {
        die("Invalid Query! All fields are empty");
    }
    $sql = "";
    $sql1 = "SELECT SUM(D.salary) / COUNT(D.salary) AS ANS FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no  JOIN departments B on C.dept_no = B.dept_no JOIN salaries D ON D.emp_no = A.emp_no WHERE 1 = '1' AND B.dept_name = '" . $_REQUEST["dept"] . "' AND A.gender = 'M'";
    $male = $conn->query($sql1)->fetch_assoc()["ANS"];
    $sql2 = "SELECT SUM(D.salary) / COUNT(D.salary) AS ANS FROM employees A JOIN dept_emp C ON A.emp_no = C.emp_no  JOIN departments B on C.dept_no = B.dept_no JOIN salaries D ON D.emp_no = A.emp_no WHERE 1 = '1' AND B.dept_name = '" . $_REQUEST["dept"] . "' AND A.gender = 'F'";
    $female = $conn->query($sql2)->fetch_assoc()["ANS"];
    echo 'Female to Male pay ratio: ' . ($female / $male) * 100 . '%';
} else {
    die("Invalid Query!");
}
if ($sql != "") {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
	    foreach ($row as $val) {
                echo "$val"; 
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	    }
            echo "<br />";
        }
    } else {
        echo "0 results.";
    }
}
?>
<?php } else { ?>
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="1" />
            <label for="query1">Query 1 - Filter Employees by the following fields.</label>
            <br />
            <ul>
                <li> Employee ID: <input type="text" name="id" id="id" value="" /></li>
                <br />
                <li> Last Name: <input type="text" name="lastname" id="lastname" value="" /></li>
                <br />
                <li> Department: <input type="text" name="dept" id="dept" value="" /></li>
            </ul>
            <input type="submit" value="Submit Query 1" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="2" />
            <label for="query2">Query 2 - Sort Departments by Employee count.</label>
            <br />
            <br />
            <input type="submit" value="Submit Query 2" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="3" />
            <label for="query3">Query 3 - Display Employees within Department ordered by Tenure.</label>
            <br />
            <ul>
                <li> Department: <input type="text" name="dept" id="dept" value="" /></li>
            </ul>
            <input type="submit" value="Submit Query 3" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="4" />
            <label for="query4">Query 4 - Display Gender Ratio within a Department.</label>
            <br />
            <ul>
                <li> Department: <input type="text" name="dept" id="dept" value="" /></li>
            </ul>
            <input type="submit" value="Submit Query 4" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="5" />
            <label for="query5">Query 5 - Display Gender pay ratio in any Department.</label>
            <br />
            <ul>
                <li> Department: <input type="text" name="dept" id="dept" value="" /></li>
            </ul>
            <input type="submit" value="Submit Query 5"/>
        </form>
<?php } ?>

<?php
$conn->close();
?>
    </body>
</html>

