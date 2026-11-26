<?php $this->load->view('header'); ?>
<div class="right_col" role="main">
  <div class="">
    <!-- page content -->
    <div id="view"></div>
    <!-- /page content -->
  </div>
</div>
<?php $this->load->view('footer'); ?>
<script type="text/javascript">
  $(function worker() {
    // don't cache ajax or content won't be fresh
    $.ajaxSetup({
      cache: false,
      complete: function() {
        // Schedule the next request when the current one's complete
        setTimeout(worker, 5000);
      }
    });
    var ajax_load = "<img src='http://automobiles.honda.com/images/current-offers/small-loading.gif' alt='loading...' />";

    // load() functions
    $("#view").load('<?= site_url('home/dashboard_live'); ?>');

    // end  
  });
</script>