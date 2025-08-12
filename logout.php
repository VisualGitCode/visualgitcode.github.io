<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Display an alert and redirect to index page
echo "<script>
        alert('Logout successful.');
        window.location.replace('index.php');
      </script>";
?>
