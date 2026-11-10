<?php
    if (isset($_SESSION['msg'])) {
        echo '<div class="alert alert-info py-1 small" role="alert">' . $_SESSION['msg'] . '</div>';
        unset($_SESSION['msg']);
    }    

    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success py-1 small" role="alert">' . $_SESSION['success'] . '</div>';    
        unset($_SESSION['success']);                
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger py-1 small" role="alert">' . $_SESSION['error'] . '</div>';    
        unset($_SESSION['error']);                
    }
?>