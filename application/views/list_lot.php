<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel">
    <div class="x_title"><h2>Pengaturan Lot</h2><div class="clearfix"></div></div>
    <div class="x_content">
        <?php $this->lib->disp_msg(); ?>
        <?php if ($this->mod_lot->permission('c')): ?><p><a href="<?php echo site_url('lot/create'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Lot</a></p><?php endif; ?>
        <p>Pilih unit melalui Detail. Jumlah unit mengikuti pilihan yang sudah disimpan pada Lot Release ini.</p>
        <div class="table-responsive"><table id="datatable" class="table table-striped table-bordered">
            <thead><tr><th>Lokasi Proyek</th><th>Kode Lot</th><th>Tanggal Release</th><th>Keterangan</th><th>Jumlah Unit</th><th>Biaya Titipan</th><th>Action</th></tr></thead>
            <tbody><?php foreach ($rows as $item): ?><tr>
                <td><?php echo $item->namproj ? html_escape($item->namproj) : 'Belum dipilih (Edit)'; ?></td>
                <td><?php echo html_escape($item->kode_lot); ?></td>
                <td data-order="<?php echo html_escape($item->tanggal_release ?: ''); ?>"><?php echo $item->tanggal_release ? date('d-m-Y', strtotime($item->tanggal_release)) : '-'; ?></td>
                <td><?php echo nl2br(html_escape($item->keterangan)); ?></td>
                <td><?php echo (int)$item->jumlah_unit; ?></td>
                <td class="text-right" data-order="<?php echo (float)$item->biaya_lain; ?>" style="white-space:nowrap">Rp <?php echo number_format((float)$item->biaya_lain,2,',','.'); ?></td>
                <td><a class="btn btn-info btn-xs" href="<?php echo site_url('lot/detail/'.$item->id); ?>">Detail</a>
                    <?php if ($this->mod_lot->permission('u')): ?><a class="btn btn-primary btn-xs" href="<?php echo site_url('lot/edit/'.$item->id); ?>">Edit</a><?php endif; ?>
                    <?php if ($this->mod_lot->permission('u')): ?><button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#lot-release-modal" data-release="<?php echo html_escape(json_encode($item)); ?>">Release</button><?php endif; ?>
                    <?php if ($item->release_saved_at): ?><a class="btn btn-default btn-xs" href="<?php echo site_url('lot/cetak/'.$item->id); ?>" target="_blank" rel="noopener"><i class="fa fa-print"></i> Cetak</a><?php endif; ?>
                    <?php if ($this->mod_lot->permission('d')): ?>
                    <?php echo form_open('lot/delete', array('style'=>'display:inline', 'onsubmit'=>"return confirm('Hapus Lot ini?');")); ?>
                        <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                        <input type="hidden" name="lot_token" value="<?php echo html_escape($this->session->userdata('lot_token')); ?>">
                        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                    <?php echo form_close(); endif; ?>
                </td>
            </tr><?php endforeach; ?></tbody>
        </table></div>
    </div>
</div></div>
<?php if ($this->mod_lot->permission('u')) $this->load->view('modal_lot_release'); ?>
<?php $this->load->view('footer'); ?>
<?php if ($this->mod_lot->permission('u')): ?>
<script src="<?php echo base_url('assets/js/lot-release.js'); ?>"></script>
<?php endif; ?>
