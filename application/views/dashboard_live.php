<?php date_default_timezone_set("Asia/Bangkok"); ?>
    <div class="page-title">
      <div class="title_left">
        <h3><?= $title; ?> <small>Data per <?= date('d/m/Y H:i:s'); ?></small></h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <?php foreach ($list_proj as $row) {
      ?>
        <div class="col-md-3   widget widget_tally_box">
          <div class="x_panel fixed_height_390">
            <div class="x_title">
              <h2><?= $row->namproj; ?></h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <h2 class="line_30">
                <center>UNIT</center>
              </h2>

              <ul class="list-inline count2">
                <li>
                  <h3>
                    <?php
                    $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE projid = '" . $row->projid . "'");
                    foreach ($query->result()  as $row_count_all);
                    echo number_format($row_count_all->tot);
                    ?>
                  </h3>
                  <span>Total</span>
                </li>
                <li>
                  <h3>
                    <font color="#FF0000">
                      <?php
                      $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status IN ('BLA') AND projid = '" . $row->projid . "'");
                      foreach ($query->result()  as $row_count_bla);
                      echo number_format($row_count_bla->tot);
                      ?>
                    </font>
                  </h3>
                  <span>Blm Laku</span>
                </li>
                <li>
                  <h3>
                    <font color="#FF0000"><?php
                                          $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status IN ('BTL') AND projid = '" . $row->projid . "'");
                                          foreach ($query->result()  as $row_count_btl);
                                          echo number_format($row_count_btl->tot); ?></font>
                  </h3>
                  <span>Batal</span>
                </li>
                <li>
                  <h3>
                    <font color="#009900"><?php
                                          //$query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status NOT IN ('BTL','AKD','BLA') AND projid = '" . $row->projid . "'");
                                          $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status NOT IN ('BTL','AKD','BLA','AKS') AND projid = '" . $row->projid . "'");
                                          foreach ($query->result()  as $row_count_spr);
                                          echo number_format($row_count_spr->tot);
                                          ?></font>
                  </h3>
                  <span>SPR</span>
                </li>
                <li>
                  <h3>
                    <font color="#009900"> <?php
                                            //$query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status = 'AKD' AND projid = '" . $row->projid . "'");
                                            $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE jenis <> 'Komersial' and status IN ('AKD','AKS') AND projid = '" . $row->projid . "'");
                                            foreach ($query->result()  as $row_count_akd);
                                            echo number_format($row_count_akd->tot);
                                            ?> </font>
                  </h3>
                  <span>Akad Subsidi</span>
                </li>

                <li>
                  <h3>
                    <font color="#009900"> <?php
                                            //$query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status = 'AKD' AND projid = '" . $row->projid . "'");
                                            $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE jenis = 'Komersial' and status IN ('AKD','AKS') AND projid = '" . $row->projid . "'");
                                            foreach ($query->result()  as $row_count_akd);
                                            echo number_format($row_count_akd->tot);
                                            ?> </font>
                  </h3>
                  <span>Akad Komersial</span>
                </li>

              </ul>


              <h2 class="line_30">
                <center>CASH IN</center>
              </h2>
              <table class="countries_list">
                <tbody>
                  <tr>
                    <td>Jatuh Tempo</td>
                    <td class="fs15 fw700 text-right">
                      <font color="#FF0000">
                        <?php
                        $query = $this->db->query("SELECT sum(tsprp.total) as tot 
                         FROM tsprp 
                         INNER JOIN tsprh ON tsprp.projid = tsprh.projid AND tsprp.sprno = tsprh.sprno
                         WHERE tsprh.status <> 'BTL'
                         AND tsprp.projid = '" . $row->projid . " 'AND tsprp.status = 'BLN' AND tsprp.jtmp < '" . date('Y-m-d') . "'");
                        foreach ($query->result()  as $row_count_jtmp);
                        echo $this->lib->format_idr($row_count_jtmp->tot); ?>
                      </font>
                    </td>
                  </tr>
                  <tr>
                    <td>Uang Masuk</td>
                    <td class="fs15 fw700 text-right">
                      <font color="#009900">
                        <?php
                        $query = $this->db->query("SELECT sum(total) as tot FROM tkwt WHERE  projid = '" . $row->projid . " 'AND btl = ''");
                        foreach ($query->result()  as $row_count_in);
                        echo $this->lib->format_idr($row_count_in->tot);
                        ?> </font>
                    </td>
                  </tr>
                </tbody>
              </table>


            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
