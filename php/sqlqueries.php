<?php

    // CurrentSession table queries
    $get_all_sessions = "select * from CurrentSessions";
    function get_session_by_id($session_id) {
        return "select * from CurrentSessions where session_id='{$session_id}'";
    }
    function delete_session_by_id($session_id) {
        return "delete from CurrentSessions where session_id='{$session_id}'";
    }

?>