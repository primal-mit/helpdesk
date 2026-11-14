<?php
    function formatTicketNumber($ticket_id){
        return "TKT #" . str_pad($ticket_id, 4, "0", STR_PAD_LEFT);
    }
?>