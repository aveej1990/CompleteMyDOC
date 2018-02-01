</div>
    <!-- Javascripts-->
   <script language="javascript" type="text/javascript">
	$(function(){
		$('.sidebar-menu a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
		$('.sidebar-menu a').click(function(){
			$(this).parent().addClass('active').siblings().removeClass('active')	
		})
	})
	
   $('.count').each(function () { // Counter effects on employee etc...
		$(this).prop('Counter',0).animate({
			Counter: $(this).text()
		}, {
			duration: 4000,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
   }); // Counter effects on employee etc...
	
	/*$(function(){
		$('.top-nav a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
		$('.top-nav a').click(function(){
			$(this).parent().addClass('active').siblings().removeClass('active')	
		})
	})	*/
	</script>
    <script src="<?php echo HTTP_JS_PATH; ?>essential-plugins.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>bootstrap.min.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>plugins/pace.min.js"></script>
    <script src="<?php echo HTTP_JS_PATH; ?>main.js"></script>
	<script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo HTTP_JS_PATH; ?>plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#contactsTable').DataTable();</script>
	<script type="text/javascript">$('#organisationTable').DataTable({
        "lengthMenu": [[10,20, 60, 80,100, -1], [10,20, 60, 80,100, "All"]]
    } );</script>
	</body>
</html>