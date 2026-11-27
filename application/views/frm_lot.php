<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel">
    <div class="x_title"><h2><?php echo html_escape($title); ?></h2><div class="clearfix"></div></div>
    <div class="x_content">
        <?php if ($error): ?><div class="alert alert-danger"><?php echo html_escape($error); ?></div><?php endif; ?>
        <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <?php echo form_open('lot/save', array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo $row ? (int)$row->id : 0; ?>">
            <input type="hidden" name="lot_token" value="<?php echo html_escape($this->session->userdata('lot_token')); ?>">
            <div class="form-group"><label class="control-label col-md-3" for="projid">Lokasi Proyek *</label><div class="col-md-6"><select id="projid" name="projid" class="form-control" required>
                <option value="">Pilih lokasi proyek</option>
                <?php $selectedProject=set_value('projid', $row ? $row->projid : '', FALSE); foreach ($projects as $project): ?>
                <option value="<?php echo (int)$project->projid; ?>" <?php echo (string)$selectedProject === (string)$project->projid ? 'selected' : ''; ?>><?php echo html_escape($project->namproj); ?></option>
                <?php endforeach; ?>
            </select></div></div>
            <div class="form-group"><label class="control-label col-md-3" for="kode_lot">Kode Lot *</label><div class="col-md-6"><input id="kode_lot" name="kode_lot" class="form-control" maxlength="50" required value="<?php echo set_value('kode_lot', $row ? $row->kode_lot : ''); ?>"></div></div>
            <div class="form-group"><label class="control-label col-md-3" for="keterangan">Keterangan</label><div class="col-md-6"><textarea id="keterangan" name="keterangan" class="form-control" rows="4" maxlength="5000"><?php echo set_value('keterangan', $row ? $row->keterangan : ''); ?></textarea></div></div>
            <div class="form-group"><div class="col-md-6 col-md-offset-3"><button type="submit" class="btn btn-success">Simpan</button><a class="btn btn-default" href="<?php echo site_url('lot'); ?>">Kembali</a></div></div>
        <?php echo form_close(); ?>
    </div>
</div></div>
<?php $this->load->view('footer'); ?>
