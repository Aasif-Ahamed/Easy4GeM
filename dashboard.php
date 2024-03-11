<?php
ob_start();
include 'session.php';
/* include 'session.php'; */
include 'config.php';
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include 'btrpcss.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                scrollX: true,
                scrollY: 300,
            });
        });
    </script>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card" style="width: 100%">
                    <img src="img/totalsale.jpg" class="card-img-top" alt="Total Sales">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">In LKR</h6>
                        <?php
                        $totalsale = "SELECT SUM(soldvalue) AS `TotalSaleSum` FROM `masterdata` WHERE `createdUser`='$userid'";
                        $totalsaleres = mysqli_query($connection, $totalsale);
                        if ($totalsaleres->num_rows > 0) {
                            while ($totalsalesum = $totalsaleres->fetch_assoc()) {
                                echo 'Rs. ' . number_format($totalsalesum['TotalSaleSum'], 2);
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card" style="width:100%">
                    <img src="img/upcomingpay.jpg" class="card-img-top" alt="Total Sales">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Payments</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Payment due within 10 days</h6>
                        <?php
                        $upcomingdue = "SELECT COUNT(id) AS payDeadLine FROM `masterdata` WHERE noofdays <='10' AND `createdUser`='$userid'";
                        $upcomingdueres = mysqli_query($connection, $upcomingdue);
                        if ($upcomingdueres->num_rows > 0) {
                            while ($dueresrow = $upcomingdueres->fetch_assoc()) {
                                echo $dueresrow['payDeadLine'] . ' Payment(s) Due';
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card pb-3" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Recent Transactions</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Last 3 transactions recorded</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Buyer</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lastthree = "SELECT * FROM masterdata WHERE `createdUser`='$userid' ORDER BY `dataofsell` LIMIT 3";
                                $lastthreeres = mysqli_query($connection, $lastthree);
                                if ($lastthreeres->num_rows > 0) {
                                    while ($lastthreerow = $lastthreeres->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?php echo $lastthreerow['buyer']; ?></td>
                                            <td><?php echo $lastthreerow['dataofsell']; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center" style="width: 100%">
                    <img src="img/loginwelcomesml.jpg" class="card-img-top" alt="Total Sales">
                    <div class="card-body">
                        <button type="submit" onclick="location.href='dashboardA.php'" name="btnA" class="btn btn-outline-primary w-100">EROT</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center" style="width: 100%">
                    <img src="img/ownstone.jpg" class="card-img-top" alt="Total Sales">
                    <div class="card-body">
                        <button type="submit" onclick="location.href='dashboardB.php'" name="btnB" class="btn btn-outline-primary w-100">OWN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'btrpjs.php'; ?>
</body>

</html>