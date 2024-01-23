<?php
ob_start();
include 'session.php';
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;
//Load Composer's autoloader
require 'vendor/autoload.php';
require('vendor/tecnickcom/tcpdf/tcpdf.php');

date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EROT Stones</title>
    <?php
    include 'btrpcss.php';
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <style>
        html,
        body {
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';

    if (isset($_POST['btnInsertNew'])) {
        $invoiceNo = $_POST['invoiceNo'];
        $targetDir = "erotuploads/";
        $emailOwners = $_POST['emailOwners'];
        $phoneBuyer = $_POST['phoneBuyer'];
        $itemdesc = $_POST['itemdesc'];
        $norh = $_POST['norh'];
        $carrat = $_POST['carrat'];
        $owner = $_POST['owner'];
        $buyer = $_POST['buyer'];
        $dos = $_POST['dos'];
        $nod = $_POST['nod'];
        $soldval = $_POST['soldval'];
        $commislbl = $_POST['commislbl'];
        $sharelbl = $_POST['sharelbl'];
        $paidVal = $_POST['paidVal'];
        $fileName = '';

        $paymentdate = date('Y-m-d', strtotime($dos . ' + ' . $nod . 'days'));
        $erot = 'erot';


        $n = 10;
        function getName($n)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }

            return $randomString;
        }
        if ($itemdesc == null || empty($itemdesc)) {
    ?>
            <script>
                $.alert({
                    title: 'Oops!',
                    content: 'Please enter an item description!',
                });
            </script>
        <?php
        } else if ($carrat == null || empty($carrat)) {
        ?>
            <script>
                $.alert({
                    title: 'Oops!',
                    content: 'Please enter carrat value!',
                });
            </script>
        <?php
        } else if ($invoiceNo == null || empty($invoiceNo)) {
        ?>
            <script>
                $.alert({
                    title: 'Oops!',
                    content: 'Please enter an invoice number!',
                });
            </script>
            <?php
        } else {
            $balancepayable = '0';
            $netvalLbl = '0';
            if ($soldval == null || empty($soldval) || $paidVal == null || empty($paidVal)) {
                $balancepayable = '0';
            } else {
                $balancepayable = $soldval - $paidVal;
            }

            if ($soldval == null || empty($soldval) || $commislbl == null || empty($commislbl) || $sharelbl == null || empty($sharelbl)) {
                $netvalLbl = '0';
            } else {
                $netvalLbl = $soldval - ($commislbl + $sharelbl);
            }

            if (empty($_FILES['itempic']['name'])) {
                $fileName = 'N/A';
                $insertmtdt = "INSERT INTO `masterdata`(`invNo`,`rectype`,`description`, `nat_heat`, `carrat`, `owner`, `buyer`, `email`, `phone`,`dataofsell`, `noofdays`, `dateofpay`, `soldvalue`, `commis`, `shareval`,`netvalue`,`picture`, `paidval`, `balancepay`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $connection->prepare($insertmtdt);
                $stmt->bind_param('sssssssssssssssssss', $invoiceNo, $erot, $itemdesc, $norh, $carrat, $owner, $buyer, $emailOwners, $phoneBuyer, $dos, $nod, $paymentdate, $soldval, $commislbl, $sharelbl, $netvalLbl, $fileName, $paidVal, $balancepayable);
                if ($stmt->execute()) {
                    header("Location:dashboardA.php");
                } else {
            ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Whoops</strong> Couldn't Process Your Request. Error Code - <?php $connection->error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }
                $stmt->close();
            } else {
                $fileName = getName($n) . $buyer . basename($_FILES['itempic']['name']);
                $erot = 'erot';
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowTypes = array("jpg", "png", "jpeg");

                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["itempic"]["tmp_name"], $targetFilePath)) {
                        $insertmtdt = "INSERT INTO `masterdata`(`invNo`,`rectype`,`description`, `nat_heat`, `carrat`, `owner`, `buyer`, `email`, `phone`,`dataofsell`, `noofdays`, `dateofpay`, `soldvalue`, `commis`, `shareval`,`netvalue`,`picture`, `paidval`, `balancepay`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $connection->prepare($insertmtdt);
                        $stmt->bind_param('sssssssssssssssssss', $invoiceNo, $erot, $itemdesc, $norh, $carrat, $owner, $buyer, $emailOwners, $phoneBuyer, $dos, $nod, $paymentdate, $soldval, $commislbl, $sharelbl, $netvalLbl, $fileName, $paidVal, $balancepayable);
                        if ($stmt->execute()) {
                            header("Location:dashboardA.php");
                        } else {
                    ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Whoops</strong> Couldn't Process Your Request. Error Code - <?php $connection->error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                    <?php
                            if (file_exists($filename)) {
                                unlink($filename);
                            }
                        }
                        $stmt->close();
                    } else {
                        echo 'Error Uploading The File';
                    }
                } else {
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Blocked</strong> Unallowed Image Type Selected
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
    <?php
                }
            }
        }
    }
    ?>
    <h1 class="text-center">EROT STONES <i class="fa-solid fa-gem"></i></h1>
    <br>
    <div class="row mb-3 d-flex justify-content-center text-center" style="width: 100%;">
        <div class="col-md-12">
            <button type="button" name="createNewA" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i> New Record</button>
            <button type="submit" onclick="window.location.href='dashboard.php';" class="btn btn-outline-primary">Dashboard</button>
        </div>
    </div>
    <table class="table table-striped table-sm" id="myTableA">
        <thead>
            <tr>
                <th style="display: none;">#</th>
                <th>#</th>
                <th>Inv</th>
                <th>Description</th>
                <th>N/H</th>
                <th><i class="fa-solid fa-carrot" style="color: #ff9500;"></i></th>
                <th>Owner</th>
                <th>Buyer</th>
                <th>Date of Sell</th>
                <th>No of Days</th>
                <th>Date of Pay</th>
                <th>Sold Value</th>
                <th>Commision</th>
                <th>Share</th>
                <th>Net Value</th>
                <th>Amount Paid</th>
                <th>Balance</th>
            </tr>
        </thead>
        <?php
        $mdquery = "SELECT * FROM `masterdata` WHERE `rectype`='erot' ORDER BY `createtime` DESC";
        $mdqueryres = $connection->query($mdquery);
        if ($mdqueryres->num_rows > 0) {
            while ($mdrow = $mdqueryres->fetch_assoc()) {
        ?>
                <tbody>
                    <tr>
                        <td style="display: none;"><?php echo $mdrow['id'] ?></td>
                        <td>
                            <form action="" method="post">
                                <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#exampleModalEmail" data-bs-whatever="<?php echo $mdrow['id']; ?>"><i class="fa-solid fa-envelope"></i></button>

                                <a href="delrec.php?aorb=a&vmxc=<?php echo $mdrow['id']; ?>&reqtype=rmv&imgpth=<?php echo 'erotuploads/' . $mdrow['picture']; ?>" name="btnDeleteRec" class="btn btn-danger btn-sm mb-1"><i class="fa-solid fa-trash"></i></a>

                                <?php
                                if ($mdrow['paystatus'] == 'Pending') {
                                ?>
                                    <button type="submit" class="btn btn-secondary btn-sm mb-1" name="updateErot" value="<?php echo $mdrow['id']; ?>"><i class="fa-solid fa-circle-check"></i></button>
                                <?php
                                }
                                ?>
                                <button type="button" class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#invoiceModal" id-val="<?php echo $mdrow['id']; ?>">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </button>
                            </form>
                            <form action="updaterec.php" method="post">
                                <input type="hidden" name="getid" value="<?php echo $mdrow['id']; ?>">
                                <button type="submit" name="btnErotUpdate" class="btn btn-warning btn-sm mb-1"><i class="fa-solid fa-pen-to-square"></i></button>
                            </form>
                            <?php
                            if ($mdrow['picture'] == 'N/A') {
                                echo '<div class="text-center"><i class="fa-solid fa-circle-exclamation" style="color: #ff0000;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Image Not Available"></i></div>';
                            } else {
                            ?>
                                <a class="btn btn-sm btn-primary" href="erotuploads/<?php echo $mdrow['picture'] ?>" target="_blank"><i class="fa-solid fa-eye"></i></a>
                            <?php
                            }

                            ?>
                        </td>
                        <td>
                            <?php echo $mdrow['invNo']; ?>
                        </td>
                        <td>
                            <?php
                            $currentdate = strtotime(date('Y-m-d'));
                            $dateofpayment = strtotime($mdrow['dateofpay']);
                            $durationleft = $dateofpayment - $currentdate;
                            if (round($durationleft / (60 * 60 * 24)) <= 5 && $mdrow['paystatus'] == 'Pending') {
                                echo '<i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i> ' . $mdrow['description'];
                            } else {
                                echo $mdrow['description'];
                            }
                            ?>
                        </td>
                        <td><?php echo $mdrow['nat_heat'] ?></td>
                        <td><?php echo $mdrow['carrat'] ?></td>
                        <td><?php echo $mdrow['owner'] ?></td>
                        <td><?php echo $mdrow['buyer'] ?></td>
                        <td>
                            <?php
                            echo $mdrow['dataofsell'] ?>
                        </td>
                        <td>
                            <?php

                            echo $mdrow['noofdays'];

                            ?>
                        </td>
                        <td><?php echo $mdrow['dateofpay'] ?></td>
                        <td>
                            <?php
                            if ($mdrow['soldvalue'] == '' || empty($mdrow['soldvalue'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['soldvalue']);
                            }
                            ?>
                        </td>
                        <td><?php
                            if ($mdrow['commis'] == '' || empty($mdrow['commis'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['commis']);
                            }
                            ?></td>
                        <td><?php
                            if ($mdrow['shareval'] == '' || empty($mdrow['shareval'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['shareval']);
                            }
                            ?></td>
                        <td><?php
                            if ($mdrow['netvalue'] == '' || empty($mdrow['netvalue'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['netvalue']);
                            }
                            ?></td>
                        <td><?php
                            if ($mdrow['paidval'] == '' || empty($mdrow['paidval'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['paidval']);
                            }
                            ?></td>
                        <td><?php
                            if ($mdrow['balancepay'] == '' || empty($mdrow['balancepay'])) {
                                echo 'Rs. 0';
                            } else {
                                echo 'Rs.' . number_format($mdrow['balancepay']);
                            }
                            ?></td>
                    </tr>
                </tbody>
        <?php
            }
        }
        ?>
    </table>
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title mdtitle fs-5" id="exampleModalLabel2">Generate Invoice</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modalbody">
                    <form id="profileform" action="invoice.php" method="post">
                        <div class="row">

                            <input type="hidden" name="idvalue" value="">
                            <div class="col-md-6">
                                <button type="submit" formtarget="_blank" name="owner" class="btn btn-primary btn-sm w-100">Owner Invoice</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" formtarget="_blank" name="buyer" class="btn btn-primary btn-sm w-100">Customer Invoice</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal fade modal-lg" id="exampleModalEmail" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirmation Required</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="idval" class="form-control" id="recipient-name">
                        <p>You are about to send a reminder email. Kindly confirm this activity to proceed</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnSendMail" class="btn btn-primary">Send Reminder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END -->
    <!-- Modal Script -->
    <script>
        $('#invoiceModal').on('show.bs.modal', function(e) {
            var opener = e.relatedTarget; //this holds the element who called the modal
            var firstname = $(opener).attr('id-val');
            $('#profileform').find('[name="idvalue"]').val(firstname);

        });

        const exampleModal = document.getElementById('exampleModalEmail');


        if (exampleModal) {
            exampleModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget
                const recipient = button.getAttribute('data-bs-whatever')
                const modalTitle = exampleModal.querySelector('.modal-title')
                const modalBodyInput = exampleModal.querySelector('.modal-body input')
                modalBodyInput.value = recipient
            })
        }
    </script>

    <?php
    if (isset($_POST['updateErot'])) {
        $erotid = $_POST['updateErot'];
        $updateErotQuery = "UPDATE `masterdata` SET `balancepay`=0, `paystatus`='Paid' WHERE id=$erotid";
        if ($connection->query($updateErotQuery) === TRUE) {
            header('Location:dashboardA.php');
        } else {
    ?>
            <div class="alert alert-danger" role="alert">
                An Error Occured While Processing Your Request - <?php $connection->error; ?>
            </div>
            <?php
        }
    }

    if (isset($_POST['btnSendMail'])) {
        $mail = new PHPMailer(true);
        /* $mail2 = new PHPMailer(true); */
        $idval = $_POST['idval'];
        $querymail = "SELECT * FROM `masterdata` WHERE `id`=$idval";
        $querymailres = $connection->query($querymail);
        if ($querymailres->num_rows > 0) {
            while ($randRow = $querymailres->fetch_assoc()) {
                if ($randRow['email'] == null || empty($randRow['email'] || $randRow['email'] == '')) {
            ?>
                    <script>
                        $.alert({
                            title: 'Oops!',
                            content: 'Please update the email address of sender!',
                        });
                    </script>
    <?php
                } else {
                    $buyername = $randRow['buyer'];
                    $buyeremail = $randRow['email'];
                    $daysleft = $randRow['noofdays'];
                    $buydesc = $randRow['description'];
                    $buyersoldvalue = $randRow['soldvalue'];

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
        <h1  class="clearfix" style="background-color:red; color:white;">CUSTOMER INVOICE</h1>
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

                    $path = "erotuploads/" . $randRow['picture'];
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    $html .= '
        <tr>
                <td colspan="2" class="service">' . $randRow['description'] . ' (' . $randRow['nat_heat'] . ')
                </td>
                <td class="service">' . $randRow['carrat'] . ' Carrat</td>
                <td class="service"> Rs. ' . number_format($randRow['soldvalue']) . '</td>
                <td class="service">01</td>
                <td class="service">' . $randRow['paystatus'] . '</td>
                <td style="text-align:right;"> Rs.' . number_format($randRow['soldvalue']) . '</td>
            </tr>

            <tr>
                <td colspan="6" class="grand total">GRAND TOTAL</td>
                <td class="grand total"> Rs.' . number_format($randRow['soldvalue']) . '</td>
            </tr>
            <tr>
            <td colspan="7" style="text-align:center;">Payment due date - ' . $randRow['dateofpay'] . ' (' . $randRow['paystatus'] . ')</td>
            </tr>
            </tbody>
            </table>
            <div>
            <img src="' . $base64 . '">
            </div>
            ';

                    $html .= '
    <hr>
        </main>
        <footer>
            System generated invoice and is valid without the signature and seal.
        </footer>
    </body>
    
    </html>';

                    //Owner Invoice
                    $html2 = '<!DOCTYPE html>
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

                    $base64 = '';
                    if ($randRow['picture'] != 'N/A' || !empty($randRow['N/A'])) {
                        $path = "erotuploads/" . $randRow['picture'];
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $base64 = 'Image Not Available';
                    }

                    $html2 .= '
        <tr>
                <td colspan="2" class="service">' . $randRow['description'] . ' (' . $randRow['nat_heat'] . ')
                </td>
                <td class="service">' . $randRow['carrat'] . ' Carrat</td>
                <td class="service"> Rs. ' . $randRow['soldvalue'] . '<br></td>
                <td class="service">01</td>
                <td class="service">' . $randRow['paystatus'] . '</td>
                <td style="text-align:right;"> Rs.' . $randRow['soldvalue'] . '</td>
            </tr>

            <tr>
                <td colspan="6" class="grand total">GRAND TOTAL</td>
                <td class="grand total"> Rs.' . $randRow['soldvalue'] . '</td>
            </tr>
            <tr>
            <td colspan="7" style="text-align:center;">Payment due date - ' . $randRow['dateofpay'] . ' (' . $randRow['paystatus'] . ')</td>
            </tr>
            </tbody>
            </table>
            <div>
            <img src="' . $base642 . '">
            </div>
            ';

                    $html2 .= '
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
                    $file = $dompdf->output();
                    $emailFileName = 'Invoice_' . generateRandomString() . '.pdf';
                    file_put_contents($emailFileName, $file);
                    ob_end_clean();

                    /* //Owner Invoice
                $dompdf2 = new Dompdf();
                $options2 = new Options();
                $dompdf2->load_html($html2);
                $dompdf2->setPaper('A4', 'Landscape');
                $options2->set('isRemoteEnabled', true);
                $dompdf2->render();
                $file2 = $dompdf2->output();
                $ownerFileName = 'OwnerInvoice_' . generateRandomString() . '.pdf';
                file_put_contents($ownerFileName, $file2);
                ob_end_clean(); */

                    try {
                        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'node236.r-usdatacenter.register.lk';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'admin@easy4gem.com';                     //SMTP username
                        $mail->Password   = 'zt3$1E*!yeWq';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        $mail->setFrom('admin@easy4gem.com', 'Easy4Gem');
                        $mail->addAddress($buyeremail, $buyername);     //Add a recipient
                        $mail->addCC('hussainnotifications@gmail.com', 'Riyasath Hussain');
                        $mail->addReplyTo('hussainnotifications@gmail.com', 'Riyasath Hussain');
                        $mail->addAttachment($emailFileName);
                        //$mail->addAttachment($ownerFileName);
                        $mail->isHTML(true);                                //Set email format to HTML
                        $mail->Subject = 'Payment Reminder';
                        $mail->Body    = 'Dear ' . $buyername . ',<br><br>
                    This is a friendly reminder about an outstanding payment on your purchase of ' . $buydesc . ' is due in another ' . $daysleft . ' days. <br><br>
                    Total Amount: Rs. ' . $buyersoldvalue . '<br><br>
                    Your prompt attention to this matter is appreciated. Please let us know if you have any questions.<br><br>
                    Thank You';

                        /* $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail2->isSMTP();                                            //Send using SMTP
                        $mail2->Host       = 'node236.r-usdatacenter.register.lk';                     //Set the SMTP server to send through
                        $mail2->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail2->Username   = 'admin@easy4gem.com';                     //SMTP username
                        $mail2->Password   = 'zt3$1E*!yeWq';                               //SMTP password
                        $mail2->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail2->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        $mail2->setFrom('admin@easy4gem.com', 'Easy4Gem');
                    $mail2->addAddress('hussainnotifications@gmail.com', 'Riyasath Hussain');     //Add a recipient

                    $mail2->addAttachment($emailFileName);
                    $mail2->addAttachment($ownerFileName);         //Add attachments

                    $mail2->isHTML(true);                                  //Set email format to HTML
                    $mail2->Subject = 'Easy4Gem Invoices';
                    $mail2->Body    = 'Hi Riyasath,<br><br>

                    Please find the attachments of Owner Invoice & Customer Invoices. You are getting this email as a Payment reminder email was sent to ' . $buyername . '.<br><br>
                    ' . $buydesc . '<br>' . $daysleft . '<br> Total Amount Rs. ' . $buyersoldvalue . '
                    <br><br>
                    
                    Thank You'; */

                        if ($mail->send()) {
                            header('Location:dashboardA.php');
                            unlink(realpath($emailFileName));
                            //unlink(realpath($ownerFileName));
                        }
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
        }
    }
    ?>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create A New Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="invoiceNo" id="invoiceLbl" placeholder="Invoice No" value="<?php echo "INV-" . date('Y') . "-" . bin2hex(random_bytes(2)); ?>">
                                    <label for="invoiceLbl">Invoice No</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="itemPciture">Picture</label>
                                <input type="file" id="itemPciture" name="itempic" class="form-control">
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <textarea class="form-control" name="itemdesc" placeholder="Item Description" id="floatingTextarea3" style="height: 100px"></textarea>
                                    <label for="floatingTextarea3">Item Description</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <select class="form-select" name="norh" id="floatingSelect" aria-label="Floating label select example">
                                        <option value="Natural">Natural</option>
                                        <option value="Heat">Heat</option>
                                    </select>
                                    <label for="floatingSelect">Natural / Heat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="carrat" id="carratlabel" placeholder="Carrat">
                                    <label for="carratlabel">Carrat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="owner" id="ownerLabel" placeholder="Text">
                                    <label for="ownerLabel">Owner</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="buyer" id="buyerLabel" placeholder="Text">
                                    <label for="buyerLabel">Buyer</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" name="emailOwners" class="form-control" id="emailOwner" placeholder="name@example.com">
                                    <label for="emailOwner">Buyer Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" name="phoneBuyer" class="form-control" id="phoneBuyer" placeholder="name@example.com">
                                    <label for="phoneBuyer">Buyer Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Date Of Sell</span>
                                    <input type="date" name="dos" class="form-control" id="floatingInputGroup1" placeholder="Date Of Sell" value="<?php echo date('Y-m-d') ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" name="nod" class="form-control" id="nodLabel" placeholder="Text">
                                    <label for="nodLabel">No of Days</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="soldval" id="soldvalLabel" placeholder="Text">
                                    <label for="soldvalLabel">Sold Value</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" name="commislbl" class="form-control" id="commisionLabel" placeholder="Text">
                                    <label for="commisionLabel">Commission</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="sharelbl" id="shareLabel" placeholder="Text">
                                    <label for="shareLabel">Share</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="paidVal" id="paidVal" placeholder="Text">
                                    <label for="paidVal">Paid Amount</label>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="btnInsertNew" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'btrpjs.php'; ?>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#myTableA').DataTable({
            scrollX: true,
            scrollY: '75vh',
            fixedHeader: true
        });
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>