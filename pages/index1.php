<?php

session_start();
include'../dbconnection.php';
include("checklogin.php");
check_login();
$current_page="index1";
include 'fy.php';
include 'inc/getState.php';
// function getState($key) {
//     return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
// }

include 'getmaturity.php';

$fdsJson=getmaturity($con);


 $sql='';

 // Fill combobox logic 
 function fill_brand($con)    
 {  
      
      $output ='';  
      $sql = "SELECT CASE WHEN MONTH(created)>=4 THEN concat(YEAR(created), '-',YEAR(created)+1) ELSE concat(YEAR(created)-1,'-', YEAR(created)) END AS financial_year FROM invtest2 GROUP BY financial_year order by financial_year DESC";  

      $result = mysqli_query($con, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {  
           $output ='';
           ?>   <option value="<?php echo $row['financial_year'];?>"> <?php echo $row['financial_year']; ?> </option> 

           <?php

           echo $output;  
      }  
      //return $output;  
 }     

function indian_number_format($num) {
    $num = "".$num;
    if( strlen($num) < 4) return $num;
    $tail = substr($num,-3);
    $head = substr($num,0,-3);
    $head = preg_replace("/\B(?=(?:\d{2})+(?!\d))/",",",$head);
    return $head.",".$tail;
}



$neworder=mysqli_query($con,"SELECT count(fd.id) as count, sum(fd.finalamt) as 'turnover' FROM fd WHERE fd.fdissueddate between '$startyear-04-01' and '$endyear-03-31' and MONTH(fd.fdissueddate) = MONTH(CURRENT_DATE())");

$val=mysqli_fetch_array($neworder);


// $bouncerate=mysqli_query($con,"SELECT ROUND((COUNT(DISTINCT CASE WHEN MONTH(created) = MONTH(CURRENT_DATE) THEN invid END) - COUNT(DISTINCT CASE WHEN MONTH(created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) THEN invid END)) / COUNT(DISTINCT CASE WHEN MONTH(created) = MONTH(CURRENT_DATE) THEN invid END) * 100, 2) AS bounce_rate FROM invtest2 WHERE (MONTH(created) = MONTH(CURRENT_DATE) OR MONTH(created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)) AND YEAR(created) = YEAR(CURRENT_DATE)");

// $getval=mysqli_fetch_array($bouncerate);

// $val2=$getval[0];


$newclients=mysqli_query($con,"SELECT count(c_name) FROM client WHERE created between '$startyear-04-01' and '$endyear-03-31' and MONTH(created) = MONTH(CURRENT_DATE())");
$val3=mysqli_fetch_array($newclients);


$actval4=indian_number_format($val['turnover']);



//$invtotal=mysqli_query($con,"SELECT count(invid),sum(totalitems),sum(totalamount),sum(taxamount) FROM `invtest2` where created between '$startyear-04-01' and '$endyear-03-30'");

//$invval=mysqli_fetch_array($invtotal);


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  
<!--     <link rel="stylesheet" type="text/css" href="amplitude audio/css/app.css"/>

     <script type="text/javascript" src="amplitude audio/dist/amplitude.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsmediatags/3.9.5/jsmediatags.min.js"></script> -->


   
  <?php include_once "links.php"; ?>
  <!-- Morris chart -->
  <link rel="stylesheet" href="../bower_components/morris.js/morris.css">
  

<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Morris.js charts -->
<script src="../bower_components/raphael/raphael.min.js"></script>
<script src="../bower_components/morris.js/morris.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans);


/*    .cover_art{
      height: 200px !important;

    }
*/


  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini
<?php echo getState('fixed-layout') ? 'fixed ' : ''; ?>
    <?php echo getState('boxed-layout') ? 'layout-boxed ' : ''; ?>
    <?php echo getState('sidebar-collapse') ? 'sidebar-collapse ' : ''; ?>
    <?php echo getState('expand-on-hover') ? 'expandOnHover ' : ''; ?>
    <?php echo getState('control-sidebar-open') ? 'control-sidebar-open ' : ''; ?>
    <?php echo getState('sidebar-skin-toggle') ? 'sidebar-light ' : ''; ?>"> 

<!-- <div id="loader"></div>
 -->
<div class="wrapper">

 <?php include_once"header.php";?>

  <?php include_once"navbar.php";?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $val['count']; ?></h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- <div class="col-lg-3 col-xs-6">

          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $val2; ?><sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div> -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $val3[0]; ?></h3>

              <p>FD holders added</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php 
              if ($val[0] == null || $val[0] == 0)
              {
                echo "0 Rs";
              }
              else{ echo $actval4."Rs"; }?></h3>

              <p>Matury Amt from <?php echo date('M') ?></p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.first row -->



      <div class="row">
      <div class="col-md-6 box-body">
          <div class="box box-info" style="height: 425px">
            <div class="box-header with-border">
              <h3 class="box-title"> Annual  Data Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>

            </div>
                <div class="box-body chart-responsive">
            </br>              </br>
                   <div id="ebar-chart" class="chart"  style="height: 400px;"></div>
               </div>
            <!-- /.box-body -->
          </div>
            <!-- /.box -->
        </div>       


        

       <div class="col-md-3 box-body" style="padding-right: 16px;">
          
          <!-- BAR CHART -->
          <div class="box box-info" style="height: 425px;">
            <div class="box-header with-border">
              <h3 class="box-title">Visitors </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div id='main' class='chart_morris' style="height: 300px;"></div>
            <div id='chart_pie_6_legend' class='text-center'></div>
            </div>
            <!-- /.box-body -->
          </div>
            <!-- /.box -->
        </div>       




          
          <div class="col-md-3" style="margin-top: 10px;">
          <div class="box box-solid bg-green-gradient">
            <div class="box-header">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">Calendar</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <div class="btn-group">

                </div>
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <!--The calendar -->
              <div id="calendar" style="width: 100%"></div>
            </div>
            <!-- /.box-body -->
  
          </div>
          </div>
        </div>




    </section>
    <!-- /.content -->
  </div>
  <?php include_once"footer.php";?>

 <?php include_once"settings.php"; ?>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<!-- Bootstrap WYSIHTML5 -->
<!-- <script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script> -->

 <!-- Include Style Sheet -->
    


<script>

$(document).ready(function() {


$('#calendar').datepicker();



              var option = {
            tooltip: {
                trigger: 'item'
            },
            legend: {
                top: '90%',
                x: 'center',
                y: 'bottom',
        
                // left: 'center',
               // top: 'bottom', // Move the legend to the bottom
        //left: 'center',
        type: 'scroll', // Enable scrolling for the legend
        itemGap: 10 // Reduce the gap between legend items

            },
            series: [
                {
                    name: 'Access From',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: true,
                    padAngle: 5,
                    itemStyle: {
                        borderRadius: 10
                    },
                    label: {
                        show: true,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show:false,
                            fontSize: 40,
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: true
                    },
                    data: [
                        { value: 1048, name: 'Search Engine' },
                        { value: 735, name: 'Direct' },
                        { value: 580, name: 'Email' },
                        { value: 484, name: 'Union Ads' },
                        { value: 300, name: 'Video Ads' }
                    ]
                }
            ]
        };

        // Initialize the chart
        var chartDom = document.getElementById('main');
        var myChart = echarts.init(chartDom);
        myChart.setOption(option);  

});

    
 
function randomData() {
  now = new Date(+now + oneDay);
  value = value + Math.random() * 21 - 10;
  return {
    name: now.toString(),
    value: [
      [now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'),
      Math.round(value)
    ]
  };
}
let data = [];
let now = new Date(2024, 9, 3);
let oneDay = 24 * 3600 * 1000;
let value = Math.random() * 1000;
for (var i = 0; i < 1000; i++) {
  data.push(randomData());
}
option = {
  title: {
    text: 'Dynamic Data & Time Axis'
  },
  tooltip: {
    trigger: 'axis',
    formatter: function (params) {
      params = params[0];
      var date = new Date(params.name);
      return (
        date.getDate() +
        '/' +
        (date.getMonth() + 1) +
        '/' +
        date.getFullYear() +
        ' : ' +
        params.value[1]
      );
    },
    axisPointer: {
      animation: false
    }
  },
  xAxis: {
    type: 'time',
    splitLine: {
      show: false
    }
  },
  yAxis: {
    type: 'value',
    boundaryGap: [0, '100%'],
    splitLine: {
      show: false
    }
  },
  series: [
    {
      name: 'Fake Data',
      type: 'line',
      showSymbol: false,
      data: data
    }
  ]
};
setInterval(function () {
  for (var i = 0; i < 5; i++) {
    data.shift();
    data.push(randomData());
  }
  myChart.setOption({
    series: [
      {
        data: data
      }
    ]
  });
}, 1000);

var chartDom = document.getElementById('ebar-chart');
        var myChart = echarts.init(chartDom);
        myChart.setOption(option);


  // Get the FD details from PHP
            var fds = <?php echo $fdsJson; ?>;
            console.log(fds);
            
            if (fds.length > 0) {
                var fdDetails = fds.map(function(fd) {
                    return "\n ID: " + fd.id + ", Name: " + fd.fdholder + ", Maturity Date: " + fd.maturitydate;
                }).join("\n");


                 // Check local storage for the reminder timestamp
                var reminderTimestamp = localStorage.getItem('reminderTimestamp');
                var currentTime = new Date().getTime();
                var oneHourInMillis = 3600000;

 function showInitialAlert() {
                    Swal.fire({
                        title: 'Maturity Alert',
                        text: 'The following FDs are maturing today:\n' + fdDetails,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Set the reminder timestamp in local storage
                            localStorage.setItem('reminderTimestamp', new Date().getTime());

                            // Set a reminder to show the alert again after 1 hour
                            setTimeout(function() {
                                Swal.fire({
                                    title: 'Reminder',
                                    text: 'Reminder: The following FDs are maturing today:\n' + fdDetails,
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                });
                            }, oneHourInMillis);
                        }
                    });
                }

                // Show the initial alert if no reminder timestamp is set or if 1 hour has passed since the last reminder
                if (!reminderTimestamp || (currentTime - reminderTimestamp >= oneHourInMillis)) {
                    showInitialAlert();
                } else {
                    // Set a timeout to show the reminder alert if less than 1 hour has passed
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Reminder',
                            text: 'Reminder: The following FDs are maturing today:\n' + fdDetails,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }, oneHourInMillis - (currentTime - reminderTimestamp));
                }
            }
        
      
// function addCommas(numberString) {
//   numberString += '';
//   var x = numberString.split('.'),
//       x1 = x[0],
//       x2 = x.length > 1 ? '.' + x[1] : '',
//       rgxp = /(\d+)(\d{3})/;

//   while (rgxp.test(x1)) {
//     x1 = x1.replace(rgxp, '$1' + ',' + '$2');
//   }

//   return x1 + x2;
// }


</script>


<!-- <script type="text/javascript" src="amplitude audio/js/functions.js"></script> -->
  

</body>
</html>
