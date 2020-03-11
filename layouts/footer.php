     </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="libs/js/functions.js"></script>
   <script src="https://kit.fontawesome.com/782d24fe68.js"></script>
 <script type="text/javascript">
 function getpdf() {
	var doc = new jsPDF();          
    var elementHandler = {
  '#ignorePDF': function (element, renderer) {
    return true;
  }
};
var source = window.document.getElementsByClassName("panel-default");
doc.fromHTML(
    source,
    15,
    15,
    {
      'width': 180,'elementHandlers': elementHandler
    });

doc.output("dataurlnewwindow");
 }
</script>
  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
