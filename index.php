<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Database Control Panel</title>
    <link type="text/css" rel="stylesheet" href="ola6.css">
</head>
<body>

    <h1>CSCI4410 Student Database</h1>

    <div class="control-panel">
        <form method="post">
            <button name="display_all">Display All Students</button>
            <button name="display_male">Display Male Students</button>
            <button name="display_female">Display Female Students</button>
            <button name="display_older_21">Display Students Older Than 21</button>
            <button name="count_majors">Count Distinct Majors</button>
            <button name="display_no_phone">Display Students Without Phone Numbers</button>
            <button name="show_insert_form">Insert New Student</button>
            <button name="show_delete_form">Delete Student</button>
            <button name="show_update_form">Update Phone Number</button>
        </form>
    </div>

<div class="results-area">
        <?php
        $servername = getenv('DB_HOST');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_NAME');
        $port = getenv('DB_PORT');

        $conn = new mysqli($servername, $username, $password, $database, $port);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function displayTable($result) {
            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<tr>";
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                echo "</tr>";
                
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $data) {
                        if ($data === null) {
                            echo "<td class='null-text'>NULL</td>";
                        } else {
                            echo "<td>" . htmlspecialchars($data) . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No records found.</p>";
            }
        }
        //all students
        if (isset($_POST['display_all'])) {
            echo "<h2>All Students</h2>";
            $sql = "SELECT * FROM Students";
            displayTable($conn->query($sql));
        }
        //male students
        if (isset($_POST['display_male'])) {
            echo "<h2>Male Students</h2>";
            $sql = "SELECT * FROM Students WHERE Gender = 'Male'";
            displayTable($conn->query($sql));
        }
        //female students
        if (isset($_POST['display_female'])) {
            echo "<h2>Female Students</h2>";
            $sql = "SELECT * FROM Students WHERE Gender = 'Female'";
            displayTable($conn->query($sql));
        }
        //older than 21
        if (isset($_POST['display_older_21'])) {
            echo "<h2>Students Older Than 21</h2>";
            $sql = "SELECT * FROM Students WHERE Age > 21";
            displayTable($conn->query($sql));
        }
        //majors unique 
        if (isset($_POST['count_majors'])) {
            echo "<h2>Distinct Majors Count</h2>";
            $sql = "SELECT COUNT(DISTINCT Major) AS 'Number of Distinct Majors' FROM Students";
            displayTable($conn->query($sql));
        }
        //no phone nums
        if (isset($_POST['display_no_phone'])) {
            echo "<h2>Students Without Phone Numbers</h2>";
            $sql = "SELECT * FROM Students WHERE Phone IS NULL";
            displayTable($conn->query($sql));
        }
        //insert 
        if (isset($_POST['show_insert_form'])) {
            echo "<h2>Insert New Student</h2>";
            echo '<form method="post" class="action-form">';
            echo '<input type="text" name="Name" placeholder="Name" required>';
            echo '<input type="text" name="BlueCard" placeholder="BlueCard ID" required>';
            echo '<input type="text" name="Major" placeholder="Major" required>';
            echo '<input type="text" name="ClassLevel" placeholder="Class Level" required>';
            echo '<input type="email" name="Email" placeholder="Email" required>';
            echo '<input type="text" name="Gender" placeholder="Gender" required>';
            echo '<input type="number" name="Age" placeholder="Age" required>';
            echo '<input type="text" name="Phone" placeholder="Phone (Optional)">';
            echo '<button type="submit" name="insert_student" class="submit-btn">Insert Student</button>';
            echo '</form>';
        }
        //delete
        if (isset($_POST['show_delete_form'])) {
            echo "<h2>Delete Student</h2>";
            echo '<form method="post" class="action-form">';
            echo '<input type="text" name="BlueCard" placeholder="Enter BlueCard ID to delete" required>';
            echo '<button type="submit" name="delete_student" class="submit-btn delete-btn">Delete</button>';
            echo '</form>';
        }
        //update phone
        if (isset($_POST['show_update_form'])) {
            echo "<h2>Update Phone Number</h2>";
            echo '<form method="post" class="action-form">';
            echo '<input type="text" name="BlueCard" placeholder="Enter BlueCard ID" required>';
            echo '<input type="text" name="Phone" placeholder="Enter New Phone Number" required>';
            echo '<button type="submit" name="update_student" class="submit-btn">Update Phone</button>';
            echo '</form>';
        }
        
        if (isset($_POST['insert_student'])) {
            $name = $_POST['Name'];
            $bluecard = $_POST['BlueCard'];
            $major = $_POST['Major'];
            $class = $_POST['ClassLevel'];
            $email = $_POST['Email'];
            $gender = $_POST['Gender'];
            $age = $_POST['Age'];
            $phone = !empty($_POST['Phone']) ? "'" . $conn->real_escape_string($_POST['Phone']) . "'" : "NULL";

            $sql = "INSERT INTO Students (Name, BlueCard, Major, ClassLevel, Email, Gender, Age, Phone) 
                    VALUES ('$name', '$bluecard', '$major', '$class', '$email', '$gender', $age, $phone)";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>New student record inserted successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }

        if (isset($_POST['delete_student'])) {
            $bluecard = $conn->real_escape_string($_POST['BlueCard']);
            $sql = "DELETE FROM Students WHERE BlueCard = '$bluecard'";
            
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    echo "<p class='success'>Student with BlueCard '$bluecard' deleted successfully!</p>";
                } else {
                    echo "<p class='error'>No student found with that BlueCard ID.</p>";
                }
            } else {
                echo "<p class='error'>Error deleting record: " . $conn->error . "</p>";
            }
        }

        if (isset($_POST['update_student'])) {
            $bluecard = $conn->real_escape_string($_POST['BlueCard']);
            $phone = $conn->real_escape_string($_POST['Phone']);
            
            $sql = "UPDATE Students SET Phone = '$phone' WHERE BlueCard = '$bluecard'";
            
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    echo "<p class='success'>Phone number updated successfully for BlueCard '$bluecard'!</p>";
                } else {
                    echo "<p class='error'>No student found with that BlueCard ID.</p>";
                }
            } else {
                echo "<p class='error'>Error updating record: " . $conn->error . "</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>