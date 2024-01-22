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
    <title>Easy4Gem | Reports</title>
    <?php
    include 'btrpcss.php';
    ?>
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
    ?>
    <form action="" method="post">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h1>Monthly Data</h1>
                    <div class="input-group">
                        <select class="form-select" name="dataType" id="inputGroupSelect05" aria-label="Example select with button addon">
                            <option selected value="erot">EROT</option>
                            <option value="own">OWN</option>
                        </select>
                        <select class="form-select" name="monthVal" id="inputGroupSelect04" aria-label="Example select with button addon">
                            <option selected value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select class="form-select" name="yearValMth" id="inputGroupSelect04" aria-label="Example select with button addon">
                            <?php
                            $fetchYear = "SELECT DISTINCT YEAR(createtime) AS FetchedYearMD FROM `masterdata`";
                            $fetchYearRes = $connection->query($fetchYear);
                            if ($fetchYearRes->num_rows > 0) {
                                while ($fetchedYears = $fetchYearRes->fetch_assoc()) {
                            ?>
                                    <option value="<?php echo $fetchedYears['FetchedYearMD']; ?>"><?php echo $fetchedYears['FetchedYearMD']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="monthData" class="btn btn-outline-success" type="button">Search</button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <form action="" method="post">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h1>Yearly Data</h1>
                                    <div class="input-group">
                                        <select class="form-select" name="dataTypeY" id="inputGroupSelect05" aria-label="Example select with button addon">
                                            <option selected value="erot">EROT</option>
                                            <option value="own">OWN</option>
                                        </select>
                                        <select class="form-select" name="yearVal" id="inputGroupSelect04" aria-label="Example select with button addon">
                                            <?php
                                            $fetchYear = "SELECT DISTINCT YEAR(createtime) AS FetchedYearMD FROM `masterdata`";
                                            $fetchYearRes = $connection->query($fetchYear);
                                            if ($fetchYearRes->num_rows > 0) {
                                                while ($fetchedYears = $fetchYearRes->fetch_assoc()) {
                                            ?>
                                                    <option value="<?php echo $fetchedYears['FetchedYearMD']; ?>"><?php echo $fetchedYears['FetchedYearMD']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" name="yearData" class="btn btn-outline-success" type="button">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
    <?php
    if (isset($_POST['monthData'])) {
        $monthVal = $_POST['monthVal'];
        $dataType = $_POST['dataType'];
        $yearValMth = $_POST['yearValMth'];
    ?>
        <div class="container">
            <div class="row">
                <hr>
                <div class="col-md-12 mb-3 text-center">
                    <h5 class="card-title">Displaying Data For <?php $monthName = date('F', mktime(0, 0, 0, $monthVal, 10)) . ' For Year ' . $yearValMth;
                                                                echo $monthName; ?> | <?php echo strtoupper($dataType) . ' Stones'; ?>
                    </h5>
                </div>
                <hr>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales</h5>
                            <?php
                            $totalSale = "SELECT COUNT(id) AS TotalSales FROM `masterdata` WHERE MONTH(dataofsell) = '$monthVal' AND YEAR(`createtime`)='$yearValMth' AND `rectype`='$dataType'";
                            $totalSaleres = $connection->query($totalSale);
                            if ($totalSaleres->num_rows > 0) {
                                while ($totalSaleRow = $totalSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $totalSaleRow['TotalSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Completed Sales</h5>
                            <?php
                            $paidSale = "SELECT COUNT(id) AS paidSales FROM `masterdata` WHERE MONTH(dataofsell) = '$monthVal' AND YEAR(`createtime`)='$yearValMth' AND `rectype`='$dataType' AND `paystatus`='Paid'";
                            $paidSaleres = $connection->query($paidSale);
                            if ($paidSaleres->num_rows > 0) {
                                while ($paidSaleRow = $paidSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $paidSaleRow['paidSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Pending Sales</h5>
                            <?php
                            $pendingSale = "SELECT COUNT(id) AS pendingSales FROM `masterdata` WHERE MONTH(dataofsell) = '$monthVal' AND YEAR(`createtime`)='$yearValMth' AND `rectype`='$dataType' AND `paystatus`='Pending'";
                            $pendingSaleres = $connection->query($pendingSale);
                            if ($pendingSaleres->num_rows > 0) {
                                while ($pendingSaleRow = $pendingSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $pendingSaleRow['pendingSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Owner</th>
                                <th>Buyer</th>
                                <th>Sold Value</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php
                        $fetchpendingTable = "SELECT * FROM `masterdata` WHERE `paystatus`='Pending' AND `rectype`='$dataType' AND MONTH(dataofsell) = '$monthVal' AND YEAR(`createtime`)='$yearValMth'";
                        $fetchpendingTableres = $connection->query($fetchpendingTable);
                        if ($fetchpendingTableres->num_rows > 0) {
                            while ($tblRow = $fetchpendingTableres->fetch_assoc()) {
                        ?>
                                <tbody>

                                    <tr>
                                        <td><?php echo $tblRow['description']; ?></td>
                                        <td><?php echo $tblRow['owner']; ?></td>
                                        <td><?php echo $tblRow['buyer']; ?></td>
                                        <td><?php echo $tblRow['soldvalue']; ?></td>
                                        <td><?php echo $tblRow['balancepay']; ?></td>
                                        <td><?php echo $tblRow['paystatus']; ?></td>
                                    </tr>
                                </tbody>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                No Pending Sales Found
                            </div>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <?php
    if (isset($_POST['yearData'])) {
        $yearVal = $_POST['yearVal'];
        $dataTypeY = $_POST['dataTypeY'];
    ?>
        <div class="container">
            <div class="row">
                <hr>
                <div class="col-md-12 mb-3 text-center">
                    <h5 class="card-title">Displaying Data For Year <?php echo $yearVal; ?> | <?php echo strtoupper($dataTypeY) . ' Stones'; ?>
                    </h5>
                </div>
                <hr>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales</h5>
                            <?php
                            $totalSale = "SELECT COUNT(id) AS TotalSales FROM `masterdata` WHERE `rectype`='$dataTypeY' AND YEAR(`createtime`) ='$yearVal'";
                            $totalSaleres = $connection->query($totalSale);
                            if ($totalSaleres->num_rows > 0) {
                                while ($totalSaleRow = $totalSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $totalSaleRow['TotalSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Completed Sales</h5>
                            <?php
                            $paidSale = "SELECT COUNT(id) AS paidSales FROM `masterdata` WHERE `rectype`='$dataTypeY' AND `paystatus`='Paid' AND YEAR(`createtime`) ='$yearVal'";
                            $paidSaleres = $connection->query($paidSale);
                            if ($paidSaleres->num_rows > 0) {
                                while ($paidSaleRow = $paidSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $paidSaleRow['paidSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Pending Sales</h5>
                            <?php
                            $pendingSale = "SELECT COUNT(id) AS pendingSales FROM `masterdata` WHERE  `rectype`='$dataTypeY' AND `paystatus`='Pending' AND YEAR(`createtime`) ='$yearVal'";
                            $pendingSaleres = $connection->query($pendingSale);
                            if ($pendingSaleres->num_rows > 0) {
                                while ($pendingSaleRow = $pendingSaleres->fetch_assoc()) {
                            ?>
                                    <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo $pendingSaleRow['pendingSales']; ?></h6>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    No Sales Data Found
                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Owner</th>
                                <th>Buyer</th>
                                <th>Sold Value</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php
                        $fetchpendingTable = "SELECT * FROM `masterdata` WHERE `paystatus`='Pending' AND `rectype`='$dataTypeY' AND YEAR(`createtime`) ='$yearVal'";
                        $fetchpendingTableres = $connection->query($fetchpendingTable);
                        if ($fetchpendingTableres->num_rows > 0) {
                            while ($tblRow = $fetchpendingTableres->fetch_assoc()) {
                        ?>
                                <tbody>

                                    <tr>
                                        <td><?php echo $tblRow['description']; ?></td>
                                        <td><?php echo $tblRow['owner']; ?></td>
                                        <td><?php echo $tblRow['buyer']; ?></td>
                                        <td><?php echo $tblRow['soldvalue']; ?></td>
                                        <td><?php echo $tblRow['balancepay']; ?></td>
                                        <td><?php echo $tblRow['paystatus']; ?></td>
                                    </tr>
                                </tbody>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                No Pending Sales Found
                            </div>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <?php include 'btrpjs.php'; ?>
</body>

</html>