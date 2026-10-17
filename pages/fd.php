<?php
session_start();
include 'dbconnection.php';
include("checklogin.php");
check_login();

include 'inc/getState.php';
$current_page = "accounts";
$current_page1 = "Fixed Assets";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Data Tables</title>

  <?php include_once "links.php"; ?>

  <script type="text/javascript" src="script/dataTables.export.js"></script>
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .label {
      display: inline-block;
      padding: 3px 8px;
      font-size: 12px;
      font-weight: 600;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: .25em;
    }

    .label-success {
      background-color: #00a65a;
      color: #fff;
    }

    .label-danger {
      background-color: #dd4b39;
      color: #fff;
    }

    .label-warning {
      background-color: #f39c12;
      color: #fff;
    }

    .label-default {
      background-color: #d2d6de;
      color: #444;
    }

    .btn-renew {
      min-width: 60px;
      height: 25px;
      padding: 0 5px;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini <?php echo getState('fixed-layout') ? 'fixed ' : ''; ?>
    <?php echo getState('boxed-layout') ? 'layout-boxed ' : ''; ?>
    <?php echo getState('sidebar-collapse') ? 'sidebar-collapse ' : ''; ?>
    <?php echo getState('expand-on-hover') ? 'expandOnHover ' : ''; ?>
    <?php echo getState('control-sidebar-open') ? 'control-sidebar-open ' : ''; ?>
    <?php echo getState('sidebar-skin-toggle') ? 'sidebar-light ' : ''; ?>">

<div id="loader"></div>

<div class="wrapper">

  <?php include_once "header.php"; ?>
    
  <?php include_once "navbar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Fixed Deposit Assets
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Fixed Deposits</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box box-info" style="overflow: auto;">
            <div class="box-header">
              <h3 class="box-title" style="padding-top: 10px;"> All Fixed Deposits Details</h3>
            </div>
            <!-- /.box-header -->
            <button type="button" id="btnplus" class="btn btn-success btn-sm pull-right" style="margin: 2px 20px 2px 2px;" onclick="window.location.href = 'add-fd.php';"><span class="glyphicon glyphicon-plus"> ADD FD</span></button><br>

            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr. no.</th>
                    <th>FD Issued Date</th>
                    <th>FD Holder</th>
                    <th>FD Bank</th>
                    <th>Principle Amt</th>
                    <th>Tenure (Days)</th>
                    <th>INT Rate %</th>
                    <th>INT Amt</th>
                    <th>Maturity Amt</th>
                    <th>Maturity Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be loaded via AJAX -->
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr. no.</th>
                    <th>FD Issued Date</th>
                    <th>FD Holder</th>
                    <th>FD Bank</th>
                    <th>Principle Amt</th>
                    <th>Tenure (Days)</th>
                    <th>INT Rate %</th>
                    <th>INT Amt</th>
                    <th>Maturity Amt</th>
                    <th>Maturity Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
          
              <table class="table">
                <tbody>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Principle Amt Total</strong></td>
                    <td>&nbsp;</td>
                    <td id="subtotalrow" style="font-weight: bold;"></td>
                    <td>&nbsp;</td> 
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>INT Amt Total</strong></td>
                    <td>&nbsp;</td>
                    <td id="totalIntAmt" style="font-weight: bold;"></td>
                    <td>&nbsp;</td> 
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Maturity Amt Total</strong></td>
                    <td>&nbsp;</td>
                    <td id="totalFinalAmt" style="font-weight: bold;"></td>
                    <td>&nbsp;</td> 
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>
              
              <br><br><br>
              <div class="btn-group" data-toggle="buttons" role="group">
                <input type="button" class="toggle-vis btn btn-primary" data-column="0" value="Sr. No.">
                <input type="button" class="toggle-vis btn btn-primary" data-column="1" value="FD Issued Date">
                <input type="button" class="toggle-vis btn btn-primary" data-column="2" value="FD Holder">
                <input type="button" class="toggle-vis btn btn-primary" data-column="3" value="FD Bank">
                <input type="button" class="toggle-vis btn btn-primary" data-column="4" value="Principle Amt">
                <input type="button" class="toggle-vis btn btn-primary" data-column="5" value="Tenure">
                <input type="button" class="toggle-vis btn btn-primary" data-column="6" value="INT Rate %">
                <input type="button" class="toggle-vis btn btn-primary" data-column="7" value="INT Amt">
                <input type="button" class="toggle-vis btn btn-primary" data-column="8" value="Maturity Amt">
                <input type="button" class="toggle-vis btn btn-primary" data-column="9" value="Maturity Date">
                <input type="button" class="toggle-vis btn btn-primary" data-column="12" value="Status">
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <hr>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php include_once "footer.php"; ?>
  <?php include_once "settings.php"; ?>
  
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- page script -->
<script>
  $(function () {
    var subtotal = 0;
    var totalIntAmt = 0;
    var totalFinalAmt = 0;

    $('#example1').DataTable();
    
    var table = $('#example2').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'processing': true,
      'ordering': true,
      'info': true,
      'autoWidth': false,
      'footer': true,
      'dom': "<'row'<'col-sm-3'l><'col-sm-9'<'pull-center'fB>>>rtip",
      
      buttons: [
        {
          extend: 'copyHtml5',
          text: '<i class="fa fa-files-o">&nbsp; Copy </i>',
          className: "btn-sm btn btn-danger",
          titleAttr: 'Copy',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          }
        },
        {
          text: '{ } &nbsp; JSON',
          className: "btn-sm btn btn-danger",
          titleAttr: 'JSON',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          },
          action: function (e, dt, button, config) {
            var data = dt.buttons.exportData();
            $.fn.dataTable.fileSave(
              new Blob([JSON.stringify(data)]),
              'Export.json'
            );
          }
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fa fa-file-excel-o">&nbsp; Excel</i>',
          className: "btn-sm btn btn-danger",
          titleAttr: 'Excel',
          title: 'Fixed Deposits Data',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          }
        },
        {
          extend: 'csvHtml5',
          text: '<i class="fa fa-file-text-o">&nbsp; CSV</i>',
          className: "btn-sm btn btn-danger",
          titleAttr: 'CSV',
          title: 'Fixed Deposits Data',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          }
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fa fa-file-pdf-o">&nbsp; PDF</i>',
          className: "btn-sm btn btn-danger",
          orientation: 'landscape',
          pageSize: 'A3',
          titleAttr: 'PDF',
          title: 'Fixed Deposits Data',
          customize: function(doc) {
            doc.pageMargins = [10, 10, 10, 10];
            doc.defaultStyle.fontSize = 7;
            doc.styles.tableHeader.fontSize = 7;
            doc.styles.tableFooter.fontSize = 15;
            doc.styles.title.fontSize = 15;
            
            doc.content[0].text = doc.content[0].text.trim();
            
            doc['footer'] = (function(page, pages) {
              return {
                columns: [
                  {
                    alignment: 'center',
                    text: ['Fixed Deposits Data from CodeTech Engineers'],
                  },
                  {
                    alignment: 'right',
                    text: ['page ', { text: page.toString() }, ' of ', { text: pages.toString() }],
                  }
                ],
                margin: [10, 0]
              }
            });

            var objLayout = {};
            objLayout['hLineWidth'] = function(i) { return .5; };
            objLayout['vLineWidth'] = function(i) { return .5; };
            objLayout['hLineColor'] = function(i) { return '#aaa'; };
            objLayout['vLineColor'] = function(i) { return '#aaa'; };
            objLayout['paddingLeft'] = function(i) { return 4; };
            objLayout['paddingRight'] = function(i) { return 4; };
            doc.content[1].layout = objLayout;
            
            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            doc.defaultStyle.alignment = 'center';
            doc.styles.tableHeader.alignment = 'center';
          },
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          }
        },
        {
          extend: 'print',
          text: '<i class="fa fa-print">&nbsp; Print</i>',
          className: "btn btn-sm btn-danger",
          titleAttr: 'Print',
          title: 'Fixed Deposits Data',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 12]
          }
        }
      ],
      
      "ajax": "ajax/fd-data.php",
      
      "columns": [
        {
          "data": "id",
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          mData: 'fdissueddate',
          render: function (data, type, row, meta) {
            if (data === null || data === undefined || data === '' || data === '0000-00-00') return '-';
            data = String(data);
            if (data.indexOf('00-00-0000') === 0) return '-';
            var parts = data.split('-');
            if (parts.length === 3 && parts[0] !== '00' && parts[0] !== '0000') {
              return parts[2] + '-' + parts[1] + '-' + parts[0];
            }
            return data;
          }
        },
        { mData: 'fdholder' },
        { mData: 'fdofbank' },
        {
          mData: 'principleamt',
          render: function(data) {
            return '₹' + parseFloat(data).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }
        },
        { mData: 'nodays' },
        { mData: 'intrate' },
        {
          mData: 'intamt',
          render: function(data) {
            return '₹' + parseFloat(data).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }
        },
        {
          mData: 'finalamt',
          render: function(data) {
            return '₹' + parseFloat(data).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }
        },
        {
          mData: 'maturitydate',
          render: function (data, type, row, meta) {
            if (data === null || data === undefined || data === '' || data === '0000-00-00') return '-';
            data = String(data);
            if (data.indexOf('00-00-0000') === 0) return '-';
            var parts = data.split('-');
            if (parts.length === 3 && parts[0] !== '00' && parts[0] !== '0000') {
              return parts[2] + '-' + parts[1] + '-' + parts[0];
            }
            return data;
          }
        },
        {
          mData: 'editaction',
          render: function (data, type, row, meta) {
            var fidValue = row.id;
            return '<a href="update-fd.php?fid=' + fidValue + '"><button class="btn btn-primary btn-xs" style="width:30px;height:25px"><i class="fa fa-pencil"></i></button></a>';
          }
        },
        {
          mData: 'deleteaction',
          render: function (data, type, row, meta) {
            var fidValue = row.id;
            return '<a class="btn btn-danger btn-xs delete-product" style="width:30px;height:25px" data-id="' + fidValue + '"><i class="fa fa-trash-o"></i></a>';
          }
        },
        {
          mData: "maturitydate",
          render: function (data, type, row, meta) {
            if (data === null || data === undefined || data === '' || data === '0000-00-00') return '<span class="label label-default">N/A</span>';
            data = String(data);
            if (data.indexOf('00-00-0000') === 0) return '<span class="label label-default">N/A</span>';
            
            var maturityDate = new Date(data);
            if (isNaN(maturityDate.getTime())) return '<span class="label label-default">N/A</span>';
            var today = new Date();
            
            // Set both dates to start of day for accurate comparison
            maturityDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            if (maturityDate < today) {
              return '<span class="label label-danger">Matured</span>';
            } else if (maturityDate.getTime() === today.getTime()) {
              return '<span class="label label-warning">Maturing Today</span>';
            } else {
              return '<span class="label label-success">Active</span>';
            }
          }
        },
        {
          mData: "status",
          render: function (data, type, row, meta) {
            if (row.maturitydate === null || row.maturitydate === undefined || row.maturitydate === '' || row.maturitydate === '0000-00-00') return '<span class="label label-default">N/A</span>';
            var maturityDate = new Date(String(row.maturitydate));
            if (isNaN(maturityDate.getTime())) return '<span class="label label-default">N/A</span>';
            var today = new Date();
            
            // Set both dates to start of day for accurate comparison
            maturityDate.setHours(0, 0, 0, 0);
            today.setHours(0, 0, 0, 0);
            
            // Only show renew button for matured FDs
            if (maturityDate < today) {
              return '<button class="btn btn-warning btn-xs btn-renew renew-fd" ' +
                     'data-id="' + row.id + '" ' +
                     'data-fdholder="' + row.fdholder + '" ' +
                     'data-principleamt="' + row.finalamt + '" ' +
                     'data-maturitydate="' + row.maturitydate + '">' +
                     '<i class="fa fa-refresh"></i> Renew</button>';
            } else {
              return '<span class="label label-default">Not Due</span>';
            }
          }
        }
      ],
      
      initComplete: function () {
        var btns = $('.dt-button');
        btns.addClass('btn btn-primary btn-sm btn-group');
        btns.removeClass('dt-button');

        table.rows().every(function () {
          var rowData = this.data();
          subtotal += parseFloat(rowData.principleamt) || 0;
          totalIntAmt += parseFloat(rowData.intamt) || 0;
          totalFinalAmt += parseFloat(rowData.finalamt) || 0;
        });
        
        // Display totals with currency format
        $('#subtotalrow').html('<strong>₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
        $('#totalIntAmt').html('<strong>₹' + totalIntAmt.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
        $('#totalFinalAmt').html('<strong>₹' + totalFinalAmt.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
      },
      
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      
      "drawCallback": function(settings) {
        // Recalculate totals on each draw
        var api = this.api();
        subtotal = 0;
        totalIntAmt = 0;
        totalFinalAmt = 0;
        
        api.rows({page: 'current'}).every(function () {
          var rowData = this.data();
          subtotal += parseFloat(rowData.principleamt) || 0;
          totalIntAmt += parseFloat(rowData.intamt) || 0;
          totalFinalAmt += parseFloat(rowData.finalamt) || 0;
        });
        
        $('#subtotalrow').html('<strong>₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
        $('#totalIntAmt').html('<strong>₹' + totalIntAmt.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
        $('#totalFinalAmt').html('<strong>₹' + totalFinalAmt.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</strong>');
      }
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function () {
      table.ajax.reload(null, false);
    }, 30000);
    
    // Column visibility toggle
    document.querySelectorAll('.toggle-vis').forEach((el) => {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        let columnIdx = e.target.getAttribute('data-column');
        let column = table.column(columnIdx);
        column.visible(!column.visible());
        
        // Toggle button class
        if ($(this).hasClass('btn-primary')) {
          $(this).removeClass('btn-primary').addClass('btn-danger');
        } else {
          $(this).removeClass('btn-danger').addClass('btn-primary');
        }
      });
    });
    
  });
  
  // Renew FD functionality
  // New FD: fdissueddate = PREVIOUS maturitydate, next maturity = prev maturity + selected tenure days
  $(document).on('click', '.renew-fd', function() {
    var fdId = $(this).data('id');
    var fdHolder = $(this).data('fdholder');
    var principleAmt = $(this).data('principleamt');
    var maturityDate = String($(this).data('maturitydate') || '');

    if (!maturityDate || maturityDate === '0000-00-00' || maturityDate.indexOf('00-00-0000') === 0) {
      Swal.fire({
        title: 'Cannot Renew',
        text: 'This FD has no valid maturity date. Please fix the maturity date first.',
        icon: 'error',
        confirmButtonText: 'OK'
      });
      return;
    }

    // Format the date for display (YYYY-MM-DD -> DD-MM-YYYY)
    var formattedDate = maturityDate;
    var parts = maturityDate.split('-');
    if (parts.length === 3) {
      formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
    }

    // Renew start date = previous maturity date (this becomes the new FD's issued date)
    var renewStartDate = maturityDate;
    
    Swal.fire({
      title: 'Renew Fixed Deposit',
      html: '<div class="text-left">' +
            '<p><strong>FD Holder:</strong> ' + fdHolder + '</p>' +
            '<p><strong>Maturity Amount (new principle):</strong> ₹' + parseFloat(principleAmt).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</p>' +
            '<p><strong>Previous Maturity Date (= new FD Issued Date):</strong> ' + formattedDate + '</p>' +
            '<p class="text-muted"><small>Next maturity = previous maturity + selected tenure.</small></p>' +
            '</div>',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Renew',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        // Ask for tenure
        Swal.fire({
          title: 'Select Renewal Tenure',
          html: '<p class="text-muted">New FD starts on <strong>' + formattedDate + '</strong>. Next maturity = start + tenure.</p>',
          input: 'select',
          inputOptions: {
            '30': '1 Month (30 days)',
            '91': '3 Months (91 days)',
            '182': '6 Months (182 days)',
            '273': '9 Months (273 days)',
            '365': '1 Year (365 days)',
            '730': '2 Years (730 days)',
            '1095': '3 Years (1095 days)',
            'custom': 'Custom Tenure'
          },
          inputPlaceholder: 'Select tenure period',
          showCancelButton: true,
          inputValidator: (value) => {
            if (!value) {
              return 'Please select a tenure period!';
            }
          }
        }).then((tenureResult) => {
          if (tenureResult.isConfirmed) {
            var tenure = tenureResult.value;
            
            if (tenure === 'custom') {
              // Ask for custom tenure
              Swal.fire({
                title: 'Enter Custom Tenure',
                input: 'number',
                inputLabel: 'Number of days',
                inputAttributes: {
                  min: '1',
                  max: '3650',
                  step: '1'
                },
                inputValue: '365',
                showCancelButton: true,
                inputValidator: (value) => {
                  if (!value || value < 1) {
                    return 'Please enter a valid number of days (1-3650)!';
                  }
                }
              }).then((customResult) => {
                if (customResult.isConfirmed) {
                  performRenewal(fdId, customResult.value, renewStartDate, formattedDate);
                }
              });
            } else {
              performRenewal(fdId, tenure, renewStartDate, formattedDate);
            }
          }
        });
      }
    });
  });
  
  function performRenewal(fdId, tenure, renewStartDate, formattedStartDate) {
    // Preview: next maturity = previous maturity + tenure days
    var tenureDays = parseInt(tenure, 10) || 0;
    var previewText = 'Please wait while we process your request.';
    if (renewStartDate && tenureDays > 0) {
      var sp = String(renewStartDate).split('-'); // YYYY-MM-DD
      if (sp.length === 3) {
        var startObj = new Date(parseInt(sp[0], 10), parseInt(sp[1], 10) - 1, parseInt(sp[2], 10));
        if (!isNaN(startObj.getTime())) {
          startObj.setDate(startObj.getDate() + tenureDays);
          var dd = ('0' + startObj.getDate()).slice(-2);
          var mm = ('0' + (startObj.getMonth() + 1)).slice(-2);
          var yy = startObj.getFullYear();
          previewText = 'New FD starts ' + (formattedStartDate || renewStartDate) +
                        ', next maturity: ' + dd + '-' + mm + '-' + yy + ' (' + tenureDays + ' days).';
        }
      }
    }
    // Show loading
    Swal.fire({
      title: 'Renewing FD...',
      text: previewText,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
    
    $.ajax({
      url: 'ajax/renew-fd.php',
      type: 'POST',
      data: {
        fd_id: fdId,
        tenure: tenure,
        renew_start_date: renewStartDate,
        action: 'renew'
      },
      dataType: 'json',
      success: function(response) {
        Swal.close();
        
        if (response.success) {
          Swal.fire({
            title: 'Success!',
            html: response.message,
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            // Reload the table
            $('#example2').DataTable().ajax.reload();
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      },
      error: function(xhr, status, error) {
        Swal.close();
        Swal.fire({
          title: 'Error!',
          text: 'Failed to renew FD. Please try again. Error: ' + error,
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  }
  
  // Delete FD functionality
  $(document).on('click', '.delete-product', function(e) {
    e.preventDefault();
    var productId = $(this).data('id');
    
    Swal.fire({
      title: 'Are you sure?',
      text: "This FD will be permanently deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'ajax/delete-fd.php',
          type: 'POST',
          data: { delete: parseInt(productId) },
          dataType: 'json'
        })
        .done(function(response) {
          Swal.fire({
            title: response.success ? 'Deleted!' : 'Error!',
            text: response.message,
            icon: response.success ? 'success' : 'error',
            confirmButtonText: 'OK'
          }).then(() => {
            if (response.success) {
              $('#example2').DataTable().ajax.reload();
            }
          });
        })
        .fail(function() {
          Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
        });
      }
    });
  });
  
  // Toggle button styling
  $('.btn-primary').on("click", function() {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active btn-danger').addClass('btn-primary');
    } else {
      $(this).addClass('active btn-danger').removeClass('btn-primary');
    }
  });
</script>

</body>
</html>