	<?php echo tools::notify_list(); ?>

	<script src="<?php echo base_url("vendor/jquery.ui/jquery-ui-1.10.4/ui/minified/jquery-ui.min.js"); ?>"></script>
	<!-- nestedSortable -->
	<script src="<?php echo base_url("vendor/nested-sortable/jquery.mjs.nestedSortable.js") ?>"></script>
	<script type="text/javascript" src="<?php echo base_url("vendor/ckeditor/ckeditor.js"); ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/bootstrap.min.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/forms.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/tables.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/sidebar.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/calendar.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/jquery.jstree.js") ?>"></script>
	<script src="<?php echo base_url("vendor/fancybox/jquery.fancybox.pack.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/jquery.dataTables.min.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/datatables.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/bootstrap-switch.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/bootstrap-datetimepicker.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/jquery.pnotify.min.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/select2.min.js") ?>"></script>
	<script src="<?php echo base_url("js/admin.js") ?>"></script>


	<?php if ( !isset($_GET["mod"]) || $_GET["mod"] == "home" ): ?>
	<!-- home scripts -->
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/numeral.min.js") ?>"></script>
	<!-- script src="<?php echo base_url("vendor/vdr.ui/js/vdr/dashboard.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/dashdemo.js") ?>"></script -->
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/raphael-min.js") ?>"></script>
	<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/morris.min.js") ?>"></script>	
	<!-- /home scripts -->
	<?php endif ?>

</body>
</html>