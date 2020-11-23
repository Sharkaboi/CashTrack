<?php 
        if(!isset($_SESSION)) { 
            session_start(); 
        }
        
        // check if session active 
        if(!isset($_SESSION['username'])){
            navigate_to_login_page("Not logged in");
        } else {
          $username = $_SESSION['username'];
          $query = get_balance_graph_data($conn,$username);
          $result = mysqli_query($conn,$query);
          if(!$result) {
            show_alert("Data could not be fetched!");
          } else {
            open_js_block();
            generate_balance_line_chart_js_from_result($result);
          }

          $query = get_spending_analysis_graph_data($conn,$username);
          $result = mysqli_query($conn,$query);
          if(!$result) {
            show_alert("Data could not be fetched!");
          } else {
            generate_expenditure_pie_chart_js_from_result($result);
          }

          $query = get_balances($conn,$username);
          $result = mysqli_query($conn,$query);
          if(!$result) {
            show_alert("Data could not be fetched!");
          } else {
            generate_breakdown_bar_chart_js_from_result($result);
          }

          $query = get_common_descriptions($conn,$username);
          $result = mysqli_query($conn,$query);
          if(!$result) {
            show_alert("Data could not be fetched!");
          } else {
            generate_common_description_table_js_from_result($result);
          }

          setup_js_event_handlers();
          close_js_block();
        }
        
        function open_js_block() {
          echo '<script type="text/javascript">';
          init_charts();
        }

        function init_charts() {
          $js = "google.charts.load('current', {packages: ['corechart','table']}); google.charts.setOnLoadCallback(drawBalanceLineChart); google.charts.setOnLoadCallback(drawExpenditurePieChart); google.charts.setOnLoadCallback(drawBreakdownBarChart); google.charts.setOnLoadCallback(drawDescriptionsTable); ";
          echo "$js";
        }

        function generate_balance_line_chart_js_from_result($result) {
          $js = " function drawBalanceLineChart() { var data = new google.visualization.DataTable(); data.addColumn('date', 'Date'); data.addColumn('number', 'Total Balance'); data.addRows([ ";
          while($row = mysqli_fetch_assoc($result)) {
            $balance = $row["balance_after"];
            $date = date_create_from_format( "Y-m-d H:i:s", $row["log_date"], timezone_open('IST'));
            $js_date = date_format($date,"Y-m-d H:i:s");
            $js .= "[new Date('$js_date'), $balance], ";
          }
          $js .= " ]); var options = { hAxis: { title: 'Time' }, vAxis: { title: 'Amount' }, explorer: { axis: 'horizontal', keepInBounds: true, maxZoomIn: 4.0 },  legend: { position: 'none' }, pointSize: 5 }; var chart = new google.visualization.LineChart(document.getElementById('div_balance_line_chart')); chart.draw(data, options); } "; 
          echo "$js";
        }

        function generate_expenditure_pie_chart_js_from_result($result) {
          $js = " function drawExpenditurePieChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Type'); data.addColumn('number', 'Percentage'); data.addRows([ ";
          $row = mysqli_fetch_assoc($result);
          $count_add = $row["count_add"];
          $count_sub = $row["count_sub"];
          $js .= "['Expenses', $count_sub], ";
          $js .= "['Income', $count_add]";
          $js .= " ]); var options = { is3D: true }; var chart = new google.visualization.PieChart(document.getElementById('div_expenditure_pie_chart')); chart.draw(data, options); } "; 
          echo "$js";
        }

        function generate_breakdown_bar_chart_js_from_result($result) {
          $js = " function drawBreakdownBarChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Account'); data.addColumn('number', 'Balance'); data.addColumn({ role: 'style' }); data.addRows([ ";
          $row = mysqli_fetch_assoc($result);
          $credit_bal = $row["credit_bal"];
          $debit_bal = $row["debit_bal"];
          $cash_bal = $row["cash_bal"];
          $js .= "['Cash', $cash_bal, '#05B8CC'], ";
          $js .= "['Debit', $debit_bal, '#68838B'],";
          $js .= "['Credit', $credit_bal, '#0D4F8B']";
          $js .= " ]); var options = {  hAxis: { title: 'Balance', minValue: 0 }, vAxis: { title: 'Account' }, bar: {groupWidth: '95%'}, legend: { position: 'none' } }; var chart = new google.visualization.BarChart(document.getElementById('div_balance_breakdown')); chart.draw(data, options); } "; 
          echo "$js";
        }

        function generate_common_description_table_js_from_result($result) {
          $js = " function drawDescriptionsTable() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Description'); data.addColumn('number', 'Count'); data.addRows([ ";
          while($row = mysqli_fetch_assoc($result)) {
            $description = $row["description"];
            $count = $row["count"];
            $js .= "['$description', $count], ";
          }
          $js .= " ]); var options = { width: '80%', height: '80%' }; var chart = new google.visualization.Table(document.getElementById('div_descriptions_count')); chart.draw(data, options); } "; 
          echo "$js";
        }

        function setup_js_event_handlers() {
          $js = "if (window.addEventListener){ window.addEventListener('resize', drawBalanceLineChart); window.addEventListener('resize', drawExpenditurePieChart); window.addEventListener('resize', drawBreakdownBarChart);} else { window.attachEvent('onresize', drawBalanceLineChart); window.attachEvent('onresize', drawExpenditurePieChart); window.attachEvent('onresize', drawBreakdownBarChart);}";
          echo "$js";
        }

        function close_js_block() {  
          echo '</script>';
        }

        function show_alert($error) {
          echo '<script>';
          echo 'alert("Error : '.$error.'");';
          echo '</script>';
        }
?>