 <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger py-1 small" role="alert">' . $_SESSION['error'] . '</div>';    
        unset($_SESSION['error']);                
    }
?>