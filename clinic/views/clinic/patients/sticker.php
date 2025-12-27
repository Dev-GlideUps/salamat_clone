<?php
$currentDate = date("d-m-Y");
?>
<html><head><meta charset="UTF-8">
    <style>
        body {
            font-size: 20px; /* Set your desired font size here */
        }
    </style>
</head>
<body>
<span> <strong>Name: </strong><?= $model->name ?></span><br/>

<span> <strong>CPR: </strong><?= $model->cpr ?></span><br/>

<span> <strong>Date: </strong><?= $currentDate ?></span>
</body>

</html>
