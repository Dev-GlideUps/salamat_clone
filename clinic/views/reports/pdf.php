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
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Date</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Invoice Number</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Doctor Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Patient Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Service Name</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Amount</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Vat</th>
            <th class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;">Total Amount</th>
        </tr>
        <?php
        

        foreach ($model as $check) {
            $subtotal = 0;
            $items = '';
            $ammount ='';

            foreach ($check->invoiceItems as $item) {
                $price = $formatter->asDecimal($item['amount'], 3);
                $subtotalAmount = $item['qty'] === null ? $item['amount'] : $item['qty'] * $item['amount'];
                $discount = 0;
                if (!empty($item['discount_unit'])) {
                    if ($item['discount_unit'] == 'percent') {
                        $discount = $subtotalAmount * ($item['discount_value'] / 100);
                    } else {
                        $discount = $item['discount_value'];
                    }
                }
                $vat = $item['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
                $subtotal += $subtotalAmount;
                $items .=  ' '.$item['item'].'<br>';
                $ammount .=  $item['qty'] === null ? $price .'<br>': "{$item['item']} = {$item['qty']} x {$price} <br> ";

            }


            // print_r($check->invoiceItems[0]);die;
            // $price = $formatter->asDecimal($check->invoiceItems[$index]['amount'], 3);
            // $subtotalAmount = $check->invoiceItems[$index]['qty'] === null ? $check->invoiceItems[$index]['amount'] : $check->invoiceItems[$index]['qty'] * $check->invoiceItems[$index]['amount'];
            // $discount = 0;
            // if (!empty($check->invoiceItems[$index]['discount_unit'])) {
            //     if ($check->invoiceItems[$index]['discount_unit'] == 'percent') {
            //         $discount = $subtotalAmount * ($check->invoiceItems[$index]['discount_value'] / 100);
            //     } else {
            //         $discount = $check->invoiceItems[$index]['discount_value'];
            //     }
            // }
            // $vat = $check->invoiceItems[$index]['vat'] ? ($subtotalAmount - $discount) * 0.10 : 0;
            // $subtotal += $subtotalAmount;
            // echo "<pre>";print_r($check->appointments);die;

        ?>
            <tr>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDate($check->created_at, 'long') ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $check->invoiceID ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= isset($check->appointments[0]) ?$check->appointments[0]->doctor->name: '' ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $check->patient->name ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $items ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $ammount ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($vat, 3) ?></td>
                <td class="mdt-subtitle-2 text-secondary" style="padding: 4pt 0;"><?= $formatter->asDecimal($check->total, 3) ?></td>
            </tr>
        <?php
        }

        ?>
    </table>

   


    
</body>
