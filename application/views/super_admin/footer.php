<footer id="sticky-footer">
    <div class="container text-center">
      <small class="font-weight-italic"><?php print $author; ?> &copy; <?php print $year; ?></small>
    </div>
</footer>

<!-- data table js-->
<script>
$(document).ready(function(){
    $('#mydata').dataTable({
        "stateSave": true,
        "ordering": false
    });
});
</script> 
</body>
</html>