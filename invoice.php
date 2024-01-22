<?php
ob_start();
include 'config.php';
date_default_timezone_set('UTC');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
//Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['owner'])) {
    $invoiceId = $_POST['idvalue'];

    $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $servername, $dbname), $username, $password);
    $stmt = $pdo->prepare("SELECT * FROM `masterdata` WHERE `id` = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT); // Bind the dynamic value
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        img {
            width: 50%;
            height:25%;
        }

        @font-face {
            font-family: Junge;
            src: url(Junge-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: Junge;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        h1 {
            color: #5D6975;
            font-family: Junge;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            margin: 0 0 2em 0;
        }

        h1 small {
            font-size: 0.45em;
            line-height: 1.5em;
            float: left;
        }

        h1 small:last-child {
            float: right;
        }

        #project {
            float: left;
        }

        #company {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.sub {
            border-top: 1px solid #C1CED9;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        table tr:nth-child(2n-1) td {
            background: #EEEEEE;
        }

        table tr:last-child td {
            background: #DDDDDD;
        }

        #details {
            margin-bottom: 30px;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
    <p style="text-align:center">Invoice Created On - ' . date('jS F Y') . '</p>
        <h1  class="clearfix" style="background-color:blue; color:white;"><b>OWNER INVOICE</b></h1>
       <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="2" class="service">ITEM</th>
                    <th class="service">CARRAT VALUE</th>
                    <th class="service">PRICE</th>
                    <th class="service">QTY</th>
                    <th class="service">STATUS</th>
                    <th style="text-align:right;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
';

    foreach ($rows as $row) {
        $base64 = '';
        if ($row['picture'] != 'N/A' || !empty($row['N/A'])) {
            $path = "erotuploads/" . $row['picture'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = 'Image Not Available';
        }

        $balancepayable = '0';
        $netvalLbl = '0';
        if ($row['soldvalue'] == null || empty($row['soldvalue']) || $row['paidval'] == null || empty($row['paidval'])) {
            $balancepayable = '0';
        } else {
            $balancepayable = $row['soldvalue'] - $row['paidval'];
        }

        if ($row['soldvalue'] == null || empty($row['soldvalue']) || $row['commis'] == null || empty($row['commis']) || $row['shareval'] == null || empty($row['shareval'])) {
            $netvalLbl = '0';
        } else {
            $netvalLbl = $row['soldvalue'] - ($row['commis'] + $row['shareval']);
        }

        $html .= '
        <tr>
                <td colspan="2" class="service">' . $row['description'] . ' (' . $row['nat_heat'] . ')
                </td>
                <td class="service">' . $row['carrat'] . ' Carrat</td>
                <td class="service"> Rs. ' . $row['netvalue'] . '<br></td>
                <td class="service">01</td>
                <td class="service">' . $row['paystatus'] . '</td>
                <td style="text-align:right;"> Rs.' . $row['soldvalue'] . '</td>
            </tr>

            <tr>
                <td colspan="6" class="grand total">GRAND TOTAL</td>
                <td class="grand total"> Rs.' . $row['soldvalue'] . '</td>
            </tr>
            <tr>
            <td colspan="7" style="text-align:center;">Payment due date - ' . $row['dateofpay'] . ' (' . $row['paystatus'] . ')</td>
            </tr>
            </tbody>
            </table>
            <div>
            <img src="' . $base64 . '">
            </div>
            ';
    }
    $html .= '
    <hr>
        </main>
        <footer>
            System generated invoice and is valid without the signature and seal.
        </footer>
    </body>
    
    </html>';

    function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $dompdf->load_html($html);
    $dompdf->setPaper('A4', 'Landscape');
    $options->set('isRemoteEnabled', true);
    $dompdf->render();
    ob_end_clean();
    $dompdf->stream('Invoice_' . generateRandomString() . '.pdf', array('Attachment' => 0));
} else if (isset($_POST['buyer'])) {
    $invoiceId = $_POST['idvalue'];

    $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $servername, $dbname), $username, $password);
    $stmt = $pdo->prepare("SELECT * FROM `masterdata` WHERE `id` = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT); // Bind the dynamic value
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        img {
            width: 50%;
            height:25%;
        }

        @font-face {
            font-family: Junge;
            src: url(Junge-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: Junge;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        h1 {
            color: #5D6975;
            font-family: Junge;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            margin: 0 0 2em 0;
        }

        h1 small {
            font-size: 0.45em;
            line-height: 1.5em;
            float: left;
        }

        h1 small:last-child {
            float: right;
        }

        #project {
            float: left;
        }

        #company {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.sub {
            border-top: 1px solid #C1CED9;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        table tr:nth-child(2n-1) td {
            background: #EEEEEE;
        }

        table tr:last-child td {
            background: #DDDDDD;
        }

        #details {
            margin-bottom: 30px;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
    <p style="text-align:center">Invoice Created On - ' . date('jS F Y') . '</p>
        <h1  class="clearfix" style="background-color:red; color:white;"><b>INVOICE</b></h1>
       <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="2" class="service">ITEM</th>
                    <th class="service">CARRAT VALUE</th>
                    <th class="service">PRICE</th>
                    <th class="service">QTY</th>
                    <th style="text-align:right;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
';

    foreach ($rows as $row) {

        $base64 = '';
        if ($row['picture'] != 'N/A' || !empty($row['N/A'])) {
            $path = "erotuploads/" . $row['picture'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = 'Image Not Available';
        }

        $html .= '
        <tr>
                <td colspan="2" class="service">' . $row['description'] . ' (' . $row['nat_heat'] . ')
                </td>
                <td class="service">' . $row['carrat'] . ' Carrat</td>
                <td class="service"> Rs. ' . $row['soldvalue'] . '</td>
                <td class="service">01</td>
                <td style="text-align:right;"> Rs.' . $row['soldvalue'] . '</td>
            </tr>

            <tr>
                <td colspan="5" class="grand total">GRAND TOTAL</td>
                <td class="grand total"> Rs.' . $row['soldvalue'] . '</td>
            </tr>
            <tr>
            <td colspan="6" style="text-align:center;">Payment due date - ' . $row['dateofpay'] . ' (' . $row['paystatus'] . ')</td>
            </tr>
            </tbody>
            </table>
            <div>
            <img src="' . $base64 . '">
            </div>
            ';
    }
    $html .= '
    <hr>
        </main>
        <footer>
            System generated invoice and is valid without the signature and seal.
        </footer>
    </body>
    
    </html>';

    function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $dompdf->load_html($html);
    $dompdf->setPaper('A4', 'Landscape');
    $options->set('isRemoteEnabled', true);
    $dompdf->render();
    ob_end_clean();
    $dompdf->stream('CustomerInvoice_' . generateRandomString() . '.pdf', array('Attachment' => 0));
} else if (isset($_POST['buyerown'])) {
    $invoiceId = $_POST['idvalue'];

    $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $servername, $dbname), $username, $password);
    $stmt = $pdo->prepare("SELECT * FROM `masterdata` WHERE `id` = :invoice_id");
    $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_INT); // Bind the dynamic value
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        img {
            width: 50%;
            height:25%;
        }

        @font-face {
            font-family: Junge;
            src: url(Junge-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: Junge;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-size: 14px;
        }

        h1 {
            color: #5D6975;
            font-family: Junge;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            border-top: 1px solid #5D6975;
            border-bottom: 1px solid #5D6975;
            margin: 0 0 2em 0;
        }

        h1 small {
            font-size: 0.45em;
            line-height: 1.5em;
            float: left;
        }

        h1 small:last-child {
            float: right;
        }

        #project {
            float: left;
        }

        #company {
            float: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.sub {
            border-top: 1px solid #C1CED9;
        }

        table td.grand {
            border-top: 1px solid #5D6975;
        }

        table tr:nth-child(2n-1) td {
            background: #EEEEEE;
        }

        table tr:last-child td {
            background: #DDDDDD;
        }

        #details {
            margin-bottom: 30px;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <main>
    <p style="text-align:center">Invoice Created On - ' . date('jS F Y') . '</p>
        <h1  class="clearfix" style="background-color:red; color:white;"><b>CUSTOMER INVOICE</b></h1>
       <table style="width: 100%;">
            <thead>
                <tr>
                    <th colspan="2" class="service">ITEM</th>
                    <th class="service">CARRAT VALUE</th>
                    <th class="service">PRICE</th>
                    <th class="service">QTY</th>
                    <th style="text-align:right;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
';

    foreach ($rows as $row) {

        $base64 = '';
        if ($row['picture'] != 'N/A' || !empty($row['N/A'])) {
            $path = "erotuploads/" . $row['picture'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = 'Image Not Available';
        }

        $html .= '
        <tr>
                <td colspan="2" class="service">' . $row['description'] . ' (' . $row['nat_heat'] . ')
                </td>
                <td class="service">' . $row['carrat'] . ' Carrat</td>
                <td class="service"> Rs. ' . $row['soldvalue'] . '</td>
                <td class="service">01</td>
                <td style="text-align:right;"> Rs.' . $row['soldvalue'] . '</td>
            </tr>

            <tr>
                <td colspan="5" class="grand total">GRAND TOTAL</td>
                <td class="grand total"> Rs.' . $row['soldvalue'] . '</td>
            </tr>
            <tr>
            <td colspan="6" style="text-align:center;">Payment due date - ' . $row['dateofpay'] . ' (' . $row['paystatus'] . ')</td>
            </tr>
            </tbody>
            </table>
            <div>
            <img src="' . $base64 . '">
            </div>
            ';
    }
    $html .= '
    <hr>
            <div id="notices">
                <div>NOTICE:</div>
                <div class="notice">Kindly settle any outstanding dues if any within the agreed time period</div>
            </div>
        </main>
        <footer>
            System generated invoice and is valid without the signature and seal.
        </footer>
    </body>
    
    </html>';

    function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $dompdf = new Dompdf();
    $options = new Options();
    $dompdf->load_html($html);
    $dompdf->setPaper('A4', 'Landscape');
    $options->set('isRemoteEnabled', true);
    $dompdf->render();
    ob_end_clean();
    $dompdf->stream('OWN_CustomerInvoice_' . generateRandomString() . '.pdf', array('Attachment' => 0));
}
