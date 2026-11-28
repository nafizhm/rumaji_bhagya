<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2><?php echo html_escape($title); ?></h2><div class="clearfix"></div></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<a class="btn btn-success" href="<?php echo site_url('sls/input_sls'); ?>">Tambah Sales</a>
<div class="table-responsive">
<table id="sales-datatable" class="table table-bordered table-striped table-hover">
<thead><tr><th>No</th><th>Kode Sales</th><th>Nama Sales</th><th>Action</th></tr></thead><tbody>
<?php foreach ($sales as $index=>$item): ?>
<tr><td><?php echo $index+1; ?></td><td><?php echo html_escape($item->kodeslsman); ?></td><td><?php echo html_escape($item->namaslsman); ?></td><td>
<a class="btn btn-warning btn-xs" href="<?php echo site_url('sls/edit').'?kode='.rawurlencode($item->kodeslsman); ?>">Edit</a>
<?php echo form_open('sls/delete',array('style'=>'display:inline','onsubmit'=>"return confirm('Hapus data sales ini?');")); ?>
<input type="hidden" name="kode" value="<?php echo html_escape($item->kodeslsman); ?>">
<input type="hidden" name="sales_token" value="<?php echo html_escape($this->session->userdata('sales_token')); ?>">
<button class="btn btn-danger btn-xs" type="submit">Hapus</button><?php echo form_close(); ?>
</td></tr>
<?php endforeach; ?>
</tbody></table>
</div></div></div></div>
<?php $this->load->view('footer'); ?>
<script>
$(function () {
    $('#sales-datatable').DataTable({
        pageLength:10,
        lengthMenu:[[10,25,50,100,-1],[10,25,50,100,'Semua']],
        order:[[1,'asc']],
        columnDefs:[{targets:[0,3],orderable:false},{targets:3,searchable:false}],
        drawCallback:function () {
            var api=this.api(), start=api.page.info().start;
            api.column(0,{page:'current'}).nodes().each(function (cell,i) { cell.textContent=start+i+1; });
        }
    });
});
</script>
