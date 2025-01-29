
<?php
function brand($conn, $selectName, $selectId, $isRequired = false) {
    // SQL query to fetch categories
    $sql = "SELECT brand FROM brand";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        echo '<select class="form-control" name="' . $selectName . '" id="' . $selectId . '"';

        // Add 'required' attribute if specified
        if ($isRequired) {
            echo ' required';
        }

        echo '>';

        // Loop through the results and create an option for each category
        while ($row = mysqli_fetch_assoc($result)) {
            $categoryName = $row['brand'];
            echo '<option value="' . $categoryName . '">&nbsp;' . $categoryName . '</option>';
            
        }

        echo '</select>';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>