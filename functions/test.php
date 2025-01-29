<!-- table_row.php -->
<tr>
    <td><?php
                                                                            include '../../connection.php';
                                                                            $db = $conn;
                                                                            $sql = 'SELECT item_code FROM product';
                                                                            $result = $db->query($sql);

                                                                            // Create an empty array to store the item_codes
                                                                            $item_codes = [];

                                                                            // Iterate over the result set and add each item_code to the array
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                $item_codes[] = $row['item_code'];
                                                                            }

                                                                            // Close the database connection
                                                                            $db->close();

                                                                            echo '<select class="col-2" id="choices-tags">';

                                                                            foreach ($item_codes as $item_code) {
                                                                                echo '<option value="' . $item_code . '">' . $item_code . '</option>';
                                                                            }
                                                                            echo '</select>';
                                                                        ?></td>
    <td>
        <div id="short-description1-container"></div>
    </td>
    <td>Data 3</td>
    <td>Data 4</td>
    <td>Data 5</td>
    <td>Data 6</td>
</tr>
<tr>
    <td><?php
                                                                            include '../../connection.php';
                                                                            $db = $conn;
                                                                            $sql = 'SELECT item_code FROM product';
                                                                            $result = $db->query($sql);

                                                                            // Create an empty array to store the item_codes
                                                                            $item_codes = [];

                                                                            // Iterate over the result set and add each item_code to the array
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                $item_codes[] = $row['item_code'];
                                                                            }

                                                                            // Close the database connection
                                                                            $db->close();

                                                                            echo '<select class="col-2" id="choices-tags">';

                                                                            foreach ($item_codes as $item_code) {
                                                                                echo '<option value="' . $item_code . '">' . $item_code . '</option>';
                                                                            }
                                                                            echo '</select>';
                                                                        ?></td>
    <td>
        <div id="short-description2-container"></div>
    </td>
    <td>Data 3</td>
    <td>Data 4</td>
    <td>Data 5</td>
    <td>Data 6</td>
</tr>


<script>
$(document).ready(function() {
    // Add an event listener to the dropdown menu
    $('#choices-tags').on('change', function() {
        // Get the selected item's value
        var selectedItem = $(this)
            .val();

        // Send an AJAX request to fetch the short description
        $.ajax({
            type: 'POST',
            url: '../../functions/fetch_short_description.php',
            data: {
                item_code: selectedItem
            },
            success: function(
                response) {
                // Update the content of the 'short-description-container' with the fetched short description
                $('#short-description1-container')
                    .html(
                        response
                    );
                $('#short-description2-container')
                    .html(
                        response
                    );
            }
        });
    });
});
</script>