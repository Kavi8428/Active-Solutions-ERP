
<?php
function product($conn,$isRequired = false) {
    // SQL query to fetch categories
    $sql = "SELECT * FROM product";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
       

        // Add 'required' attribute if specified
        if ($isRequired) {
            echo ' required';
        }

        echo '>';

        // Loop through the results and create an option for each category
        while ($row = mysqli_fetch_assoc($result)) {
            $itemcode = $row['item_code'];
            echo '<option value="' . $itemcode .'</option>';
        }

        echo '</select>';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>