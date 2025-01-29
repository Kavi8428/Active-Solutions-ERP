<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');

    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Database connection failed: " . mysqli_connect_error()]);
        exit;
    }

    $ce_name = mysqli_real_escape_string($conn, $_POST["ce_name"]);
    $dob = mysqli_real_escape_string($conn, $_POST["dob"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $tel1 = mysqli_real_escape_string($conn, $_POST["tel1"]);
    $tel2 = mysqli_real_escape_string($conn, $_POST["tel2"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $notes = mysqli_real_escape_string($conn, $_POST["notes"]);
    $company = mysqli_real_escape_string($conn, $_POST["company"]);

    $insert_ce_query = "INSERT INTO customer_employee (cus_em_name, dob, address, email, tel1, tel2, type, note) 
                        VALUES ('$ce_name', '$dob', '$address', '$email', '$tel1', '$tel2', '$type', '$notes')";

    if (mysqli_query($conn, $insert_ce_query)) {
        $ce_id = mysqli_insert_id($conn);

        $get_customer_id_query = "SELECT customer_id FROM customer WHERE company_name = '$company'";
        $customer_result = mysqli_query($conn, $get_customer_id_query);

        if ($customer_result && mysqli_num_rows($customer_result) > 0) {
            $row = mysqli_fetch_assoc($customer_result);
            $customer_id = $row["customer_id"];

            $insert_junc_query = "INSERT INTO cus_emp_junc (customer_id, cus_em_id) 
                                  VALUES ('$customer_id', '$ce_id')";

            if (mysqli_query($conn, $insert_junc_query)) {
                echo json_encode(["success" => true, "message" => "Employee added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to insert into junction table: " . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Customer not found for the given company."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert into customer_employee table: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
