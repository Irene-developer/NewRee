<?php
if (isset($_FILES['file'])) {
    $destination = '/uploads/' . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $destination);
}
?>
