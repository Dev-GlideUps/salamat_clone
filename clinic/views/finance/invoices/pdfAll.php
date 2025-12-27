<?php

use yii\helpers\Html;

$formatter = Yii::$app->formatter;


/* @var $this yii\web\View */
/* @var $model common\models\hr\Salary */
// $this->context->layout = 'plain';
// $this->title = Yii::t('finance', 'Invoice')." - {$model->invoiceID}";

// $formatter = Yii::$app->formatter;
// $photo = empty($model->staff->photoPath) ? Yii::getAlias('@frontend/web/img/person_light.jpg') : $model->staff->photoPath;


?>

<body id="root">

    <div style="padding: 20pt 0;"></div>
    <table width='800px' align="center" style="text-align: center;border:2px black solid;">
        <tr>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Date</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Invoice Number</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Doctor Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Patient Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Service Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Amount</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Discount</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Vat</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Total Amount</th>
            <!-- <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 4pt;border-right: 1px solid lightgray;">Total Sum</th> -->
        </tr>
        <?php
        $total_sum=0;
        $vat_sum=0;
        
        
        foreach($model as $sum){
            $total_sum+=$sum->total;
            $vat_sum+=$sum->vat;
        }
        
        
        foreach ($model as $index => $check) {
            $subtotal = 0;
            $items = '';
            $ammount ='';
            $vat2=[];
            $discount_per=[];
            foreach ($check->invoiceItems as $itemIndex => $item) {
                $price = $formatter->asDecimal($item['amount'], 3);
                $subtotalAmount = $item['qty'] === null ? $item['amount'] : $item['qty'] * $item['amount'];
                $discount = 0;
                if (!empty($item['discount_unit'])) {
                    if ($item['discount_unit'] == 'percent') {
                        $discount = $subtotalAmount * ($item['discount_value'] / 100);
                        // $discount = $item['discount_value'];
                    } else {
                        $discount = $item['discount_value'];
                    }
                }
                $vat = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                $discount_per[$itemIndex] = $discount;
                $vat2[$itemIndex] = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                $subtotal += $subtotalAmount;
                $items .=  ' '.$item['item'].'<br>';
                // $ammount .=  $item['qty'] === null ? $price : " $subtotalAmount  ";
                $ammount .=  $item['qty'] === null ? $price .'<br>' : $item['qty'] * $price ."<br>" ;
                // echo "<pre>";print_r($item);
                // echo "<br><pre>";print_r($vat);

               

            }
            
            // echo "<pre>";print_r($check->invoiceItems);

        ?>
            <tr>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDate($check->created_at, 'long') ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $check->invoiceID ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= count($check->appointments)>0?$check->appointments[0]->doctor->name:''; ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $check->patient->name ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $items ?></td>
                <!-- <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($subtotal,3) ?></td> -->
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $ammount ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?php foreach($discount_per as $discount){ echo "<br>" .$formatter->asDecimal($discount,3);} ?></td>
                <!-- <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?php foreach($vat2 as $vat){ echo "<br>" .$formatter->asDecimal($vat,3);} ?></td> -->

                <!-- <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($discount_per[0],3) ?></td> -->
                <!-- <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($check->vat,3) ?></td> -->
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?php foreach($vat2 as $vat){ echo "<br>" .$formatter->asDecimal($vat,3);} ?></td>

                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($check->total, 3) ?></td>
                <?php 
                // echo "<td>". $index ."</td>" ;
                // if($index>0){
                //     echo "<td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>";
                // }else{
                //     echo "<td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'>{$formatter->asDecimal($total_sum,2)}</td>";
                // }
                
                ?>
                
            </tr>
        <?php
        // echo "<td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;''>$total_sum</td>";
            // echo "<pre>";print_r();
        }
        // die;

        ?>
        <tr>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'><strong>Total Vat</strong></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'><strong>Total Sum</strong></td>
        </tr>
        <tr>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;display:none;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;display:none;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'><strong><?= $formatter->asDecimal($vat_sum,3) ?></strong></td>
        <td class='mdt-subtitle-2 text-secondary' style='padding: 4pt 0;'><strong><?= $formatter->asDecimal($total_sum,3)?></strong></td>
        </tr>
                  
    </table>
    

   


    
</body>