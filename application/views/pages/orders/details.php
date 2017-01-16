<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->

<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <?php if ($allow['update']): ?>
   <section class="content-header">
     <div>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-home"></i> / <?php echo $allow['section_name']; ?></a></li>
          <li><a href="<?php echo site_url('backend/'.$active_page); ?>"><?php echo $allow['subsection_name']; ?></a></li>
          <li class="active">Детали</li>
        </ol>
      </div>
        <h1>
          Ордер
          <small><?php echo $order['info'][0]['order_id']; ?></small>
        </h1>
    </section>

    <!-- Main content -->
    <div id="dOrders">
      <section class="invoice">
      <?php echo form_open('base_url("backend/orders/save")',array('name'=>'frmOrderDetails','id'=>'frmOrderDetails') );?>
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Mac Baker LLC
            <small class="pull-right">Date: <?php echo date('y-m-d'); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
          От
          <address>
            <strong><?php echo $order['info'][0]['client_name']; ?></strong><br>
            Почта: <?php echo $order['info'][0]['client_email']; ?><br>
            Тел.: <?php echo $order['info'][0]['client_phone']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
            <br>
            Город: <?php echo $order['info'][0]['shp_city'] ; ?><br>
            Улица: <?php echo $order['info'][0]['shp_street'] ; ?><br>
            Дом: <?php echo $order['info'][0]['shp_bld'] ; ?><br>
            Кв: <?php echo $order['info'][0]['shp_apt'] ; ?>
         <!--  To
          <address>
            <strong>John Doe</strong><br>
            795 Folsom Ave, Suite 600<br>
            San Francisco, CA 94107<br>
            Phone: (555) 539-1037<br>
            Email: john.doe@example.com
          </address> -->
        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
          <input type="hidden" name="ordTbId" value="<?php echo $order['info'][0]['id']; ?>">
            
          <b>Ордер Но.</b> #<?php echo $order['info'][0]['order_id']; ?><br>
          <b>Статус:</b> <select name="ordStatus" id="ordStatus">
                            <?php foreach($order_statuses as $ost): ?>
                              <?php if($ost['st_id'] == $order['info'][0]['o_status_id']): ?>
                                <option value="<?php echo $ost['st_id'];?>" selected="" disabled=""><?php echo $ost['name'];?></option>
                              <?php else: ?>  
                                <option value="<?php echo $ost['st_id'];?>"><?php echo $ost['name'];?></option>
                              <?php endif ?>  
                            <?php endforeach ?>
                        </select>
          <br/>
          <b>Создан:</b> <?php echo $order['info'][0]['created']; ?><br>
          <b>Изменён:</b> <?php echo $order['info'][0]['modified']; ?><br>
          <b>Тип платежа:</b> <?php echo $order['info'][0]['pmt_name']; ?><br>
        </div>
        <!-- /.col -->
         <div class="col-sm-3 invoice-col">
          <b>Статус платежа:</b> <select name="ordPmtStatus" id="ordPmtStatus">
                            <?php foreach($pmt_statuses as $pst): ?>
                              <?php if($pst['ps_id'] == $order['info'][0]['pmt_status_id']): ?>
                                <option value="<?php echo $pst['ps_id'];?>" selected="" disabled=""><?php echo  $pst['name'];?></option>
                              <?php else: ?>  
                                <option value="<?php echo $pst['ps_id'] ;?>"><?php echo $pst['name'];?></option>
                              <?php endif ?>  
                            <?php endforeach ?>
                        </select>
          <br/>
          <b>Метод доставки:</b> <?php echo $order['info'][0]['shp_type']; ?><br>
          <b>Зона:</b> <?php echo $order['info'][0]['shp_zone']; ?><br>
          <b>Период:</b> <?php echo $order['info'][0]['shp_period']; ?><br>
           
        </div>
      </div>
      <!-- /.row -->
      <hr>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-condensed">
            <thead>
            <tr>
              <th>Название</th>
              <th>Описание</th>
              <th>Аттрибуты</th>
              <th>Цена</th>
              <th>Количество</th>
              <th>Общая цена</th>
            </tr>
            </thead>
            <tbody>
              <?php if (isset($order['items']) && $order['items']): ?>
                  <?php foreach ($order['items'] as $item): ?>
                    <tr>
                      <!-- Nazvanie -->
                      <td><?php echo $item['set_name'].' ('. $item['defined_count'] .'шт.)' ?></td>
                      <!-- Opisanie produktov seta -->
                      <td>
                        <?php 
                          $temp_items = json_decode($item['set_products'], true);
                          if (isset($temp_items)) {
                              echo "<small><ol>";
                              foreach ($temp_items as $product) {
                                echo '<li>'.$product['name'] .' - '. $product['qty'] .'шт.'.'</li>';   
                              }
                              echo "</ol></small>";
                          }
                         ?>
                      </td>
                      <!-- Attributy seta -->
                      <td>
                        <?php if (isset($order['attrs']) && $order['attrs']): ?>
                            <?php echo '<ol>'; ?>
                            <?php foreach ($order['attrs'] as $attr): ?>
                                <?php if ($attr['set_id'] == $item['set_id']): ?>
                                    <?php echo '<li>' .         $attr['attrgr_name']
                                                      . ' | ' . $attr['attr_name']
                                                      . '-'   . $attr['qty'].'шт.'
                                                      . '=> ' . $attr['unit_price']
                                                      . ' '   . $attr['mmt_name']
                                              .'</li>'; 
                                    ?>    
                                <?php endif ?>
                            <?php endforeach ?>
                            <?php echo '</ol>'; ?>
                        <?php endif ?>
                      </td>
                      <!-- cena seta -->
                      <td>
                        <?php echo number_format($item['unit_price'],0,'',',') . $item['mmt_name'] ; ?>
                      </td>
                      <!-- kolichestvo seta -->
                      <td>
                        <?php echo $item['qty']; ?>
                      </td>

                      <!-- obshaja cena seta -->
                      <td>
                        <?php echo number_format($item['unit_price']*$item['qty'],0,'',',') . $item['mmt_name'] ; ?>
                      </td>
                    </tr>
                  <?php endforeach ?>
              <?php endif ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Оплата:</p>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            <i class="fa fa-money" aria-hidden="true"></i>
           <?php echo $order['info'][0]['pmt_name']; ?>
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Сумма платежа</p>
          <?php $grand_total = $order['info'][0]['total']; ?>
          <div class="table-responsive">
            <table class="table table-condensed">
              <tr>
                <th style="width:50%">Всего:</th>
                <td><?php echo number_format($order['info'][0]['total'],0,'',','); ?></td>
              </tr>
              <tr>
                <th>Купон։ <b><?php echo strtoupper($order['info'][0]['coupon_code']); ?></b></th>
                <td>-<?php
                          /*Coupon type check and grand_total recalculation*/ 
                          if (isset($order['info'][0]['coupon_type']) && $order['info'][0]['coupon_type'] == 'percent') {
                            //decrease Grand total by percent
                            $grand_total = $order['info'][0]['total']*(1-intval($order['info'][0]['coupon_discount']/100));
                            echo $order['info'][0]['coupon_discount'] . '%';
                            
                          } //decrease Grand total by By fixed ammount
                          elseif(isset($order['info'][0]['coupon_type']) && $order['info'][0]['coupon_type'] == 'fix') {

                             $grand_total = $order['info'][0]['total']-floatval($order['info'][0]['coupon_discount']);
                             echo $order['info'][0]['coupon_discount'] . 'руб.'; 
                          }
                      ?>
                </td>
              </tr>
              <tr>
                <th>Доставка:</th>
                <td><?php echo number_format( $order['info'][0]['shp_price'], 0,'',','); ?></td>
              </tr>
              <tr>
                <th>Total:</th>
                <td><?php echo number_format( $grand_total + $order['info'][0]['shp_price'], 0,'',','); ?></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
              <button type="button" class="btn btn-primary" id="btnOrdHistory" data-toggle="modal" data-target="#mdlOrdHistory">История заказa</button>
             
<!--           <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button> -->
          <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">Сохранить</button>
        </div>
      </div>
    
        
        <!-- Modal -->
        <div class="modal fade" id="mdlOrdHistory" tabindex="-1" role="dialog" aria-labelledby="mdlOrdHistoryLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mdlOrdHistoryLabel">Modal title</h4>
              </div>
              <div class="modal-body">
               <table class="table">
                 <thead>
                   <th>Дата</th>
                   <th>Описание</th>
                 </thead>
                 <tbody>
                   <?php foreach($order_history as $oh): ?>
                     <tr>
                        <td><?php echo $oh['date'];?><td>
                        <td><?php echo $oh['description'];?><td>
                      </tr>
                   <?php endforeach ?>
                 </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
    <?php echo form_close();?>  
    </section>
    </div>
    <!-- /.content -->







     <div class="clearfix"></div>
  <?php else: ?>
  <h1>You Don`t Have permission To View This page...</h1>
  <?php endif ?>
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper





