<?php 
        if(!isset($_SESSION)) { 
            session_start(); 
        }
        
        // check if session active 
        if(!isset($_SESSION['username'])){
            navigate_to_login_page("Not logged in");
        } else {
          $username = $_SESSION['username'];
          $query = get_graph_data($conn,$username);
          $result = mysqli_query($conn,$query);
          if(!$result) {
            show_alert("Data could not be fetched!");
          } else {
            generate_js_array_from_result($result);
          }
        }
        
        function generate_js_array_from_result($result) {
          $js = "google.charts.load('current', {packages: ['corechart', 'line']});google.charts.setOnLoadCallback(drawBasic); function drawBasic() { var data = new google.visualization.DataTable(); data.addColumn('date', 'Date'); data.addColumn('number', 'Total Balance'); data.addRows([ ";
          while($row = mysqli_fetch_assoc($result)) {
            $balance = $row["balance_after"];
            $date = date_create_from_format( "Y-m-d H:i:s", $row["log_date"], timezone_open('IST'));
            $js_date = date_format($date,"Y-m-d H:i:s");
            $js .= "[new Date('$js_date'), $balance], ";
          }
          $js .= " ]); var options = { hAxis: { title: 'Time' }, vAxis: { title: 'Amount' }, explorer: { axis: 'horizontal', keepInBounds: true, maxZoomIn: 4.0 }, pointSize: 5 }; var chart = new google.visualization.LineChart(document.getElementById('chart_div')); chart.draw(data, options); } if (window.addEventListener){ window.addEventListener('resize', drawBasic); } else { window.attachEvent('onresize', drawBasic); }"; 
          echo '<script type="text/javascript">';
          echo "$js";
          echo '</script>';
        }

        function show_alert($error) {
          echo '<script>';
          echo 'alert("Error : '.$error.'");';
          echo '</script>';
        }

?>