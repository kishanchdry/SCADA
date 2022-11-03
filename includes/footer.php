    
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 2021 All Right reserved and Developed by <a href="http://visionworldtech.com/" target="_blank" class="footerlink">Vision World Tech Pvt Ltd</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to log-out !</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

  

      <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

  <script src="js/custom.js"></script>
  
 
  <script src="js/table2excel.js"></script>
  <script src="js/core.js"></script>
  <script src="js/charts.js"></script>
  <script src="js/animated.js"></script>
 
  <script>

function dt() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();

    var datetoday = d.getFullYear() + '-' +
        (('' + month).length < 2 ? '0' : '') + month + '-' +
        (('' + day).length < 2 ? '0' : '') + day;

    return datetoday;

}
     if(!start_date){
         var start_date = '01';
     }

     if(!last_date){
         var last_date = dt();  
     }

var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
      , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table><tr rowspan="10"><td></td><td colspan="14"><img src="{imgsrc1}" style="float:left;clear:none;margin:30px;" width="100%" /></td></tr><tr><td></td><td colspan="14"></td></tr></table><br><br><br><br><br><br><br><br><table><tr><td></td><td colspan="14" style="border:1px solid black; "><div style="height:35px;text-align:center;display:inline-block;font-size:16px;font-weight:900;">{heading}</div><br><br></td></tr></table><br><br><table>{table}</table></body></html>'
      , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
      , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    return function(table, name) {
      if (!table.nodeType) table = document.getElementById(table)
      heading= 'Mothly Flow Report From ' + start_date +' To '+ last_date;
      imgsrc1='http://balotracetp.com/SCADA/img/scada.png';
  
      var ctx = {worksheet: name || 'Worksheet',imgsrc1: imgsrc1, heading: heading ,table: table.innerHTML}
      var blob = new Blob([format(template, ctx)]);
      var blobURL = window.URL.createObjectURL(blob);
      return blobURL;
    }
  })()
  
  $("#ecportExcel").click(function() {
      var todaysDate = dt();
      var blobURL = tableToExcel($(this).attr('title'), 'Mothly Flow');
      $(this).attr('download',$(this).attr('alt')+'_'+todaysDate+'.xls')
      $(this).attr('href',blobURL);
  });


 </script>
</body>

</html>
    
   