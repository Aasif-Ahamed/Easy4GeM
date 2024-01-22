<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Update</title>
    <?php
    include 'btrpcss.php';
    ?>
</head>

<body>
    <?php
    include 'navbar.php';

    if (isset($_POST['btnErotUpdate'])) {
        $getid = $_POST['getid'];
        $fetchdata = "SELECT * FROM `masterdata` WHERE `id`= $getid";
        $fetchdatares = $connection->query($fetchdata);
        if ($fetchdatares->num_rows > 0) {
            while ($fetchrow = $fetchdatares->fetch_assoc()) {
    ?>
                <div class="container">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mt-3">
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="invNoert" id="invoiceLblErot" placeholder="Invoice Number" value="<?php echo $fetchrow['invNo'] ?>">
                                    <label for="invoiceLblErot">Invoice Number</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <?php
                                if ($fetchrow['picture'] == 'N/A' || empty($fetchrow['picture']) || $fetchrow['picture'] == '') {
                                ?>
                                    <div class="alert alert-danger" role="alert">
                                        No images available. If required please upload an image
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <input type="hidden" name="ifimgavailable" value="<?php echo $fetchrow['picture']; ?>">
                                    <img src="erotuploads/<?php echo $fetchrow['picture'] ?>" alt="Current Picture" srcset="" class="image image-fluid" style="width: 100%;">
                                <?php
                                }

                                ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="file" name="itempic" class="form-control">
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <textarea class="form-control" name="itemdesc" placeholder="Item Description" id="floatingTextarea3" style="height: 100px"><?php echo $fetchrow['description'] ?></textarea>
                                    <label for="floatingTextarea3">Item Description</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <select class="form-select" name="norh" id="floatingSelect" aria-label="Floating label select example">
                                        <option value="<?php echo $fetchrow['nat_heat'] ?>" selected><?php echo $fetchrow['nat_heat'] ?></option>
                                        <option value="Natural">Natural</option>
                                        <option value="Heat">Heat</option>
                                    </select>
                                    <label for="floatingSelect">Natural / Heat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="carrat" id="carratlabel" placeholder="Carrat" value="<?php echo $fetchrow['carrat'] ?>">
                                    <label for="carratlabel">Carrat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="owner" id="ownerLabel" placeholder="Owner" value="<?php echo $fetchrow['owner'] ?>">
                                    <label for="ownerLabel">Owner</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="buyer" id="buyerLabel" placeholder="Buyer" value="<?php echo $fetchrow['buyer'] ?>">
                                    <label for="buyerLabel">Buyer</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" name="emailOwners" class="form-control" id="emailOwner" placeholder="name@example.com" value="<?php echo $fetchrow['email'] ?>">
                                    <label for="emailOwner">Buyer Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" name="phoneBuyer" class="form-control" id="phoneBuyer" placeholder="Buyer Mobile" value="<?php echo $fetchrow['phone'] ?>">
                                    <label for="phoneBuyer">Buyer Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Date Of Sell</span>
                                    <input type="date" name="dos" class="form-control" id="floatingInputGroup1" placeholder="Date Of Sell" value="<?php echo $fetchrow['dataofsell'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" name="nod" class="form-control" id="nodLabel" placeholder="No of Days" value="<?php echo $fetchrow['noofdays'] ?>">
                                    <label for="nodLabel">No of Days</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="soldval" id="soldvalLabel" placeholder="Sold Value" value="<?php echo $fetchrow['soldvalue'] ?>">
                                    <label for="soldvalLabel">Sold Value</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" name="commislbl" class="form-control" id="commisionLabel" placeholder="Commision" value="<?php echo $fetchrow['commis'] ?>">
                                    <label for="commisionLabel">Commission</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="sharelbl" id="shareLabel" placeholder="Share" value="<?php echo $fetchrow['shareval'] ?>">
                                    <label for="shareLabel">Share</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="paidVal" id="paidVal" placeholder="Paid Amount" value="<?php echo $fetchrow['paidval'] ?>">
                                    <label for="paidVal">Paid Amount</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary w-100" onclick="Location.href='dashboardA.php'">Cancel</button>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="idval" value="<?php echo $getid; ?>">
                                <button type="submit" name="btnUpdateRec" class="btn btn-success w-100">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <?php
            }
        }
    }

    if (isset($_POST['btnUpdateRec'])) {
        $invNoert = $_POST['invNoert'];
        $idval = $_POST['idval'];
        $ifimgavailable = $_POST['ifimgavailable'];
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
        $balancepayable = $soldval - $paidVal;
        $paymentdate = date('Y-m-d', strtotime($dos . ' + ' . $nod . 'days'));
        $netvalLbl = $soldval - ($commislbl + $sharelbl);
        $fileName = $ifimgavailable;
        $paystatus = '';
        $updateQuery = '';

        $erot = 'erot';

        if ($balancepayable === 0) {
            $paystatus = 'Paid';
        } else {
            $paystatus = 'Pending';
        }

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

        if (empty($_FILES['itempic']['name'])) {
            if (empty($ifimgavailable) || $ifimgavailable == 'N/A') {
                $fileName = 'N/A';
                $updateQuery = "UPDATE `masterdata` SET `invNo`='$invNoert', `description`='$itemdesc',`nat_heat`='$norh',`carrat`='$carrat',`owner`='$owner',`buyer`='$buyer',`email`='$emailOwners',`phone`='$phoneBuyer',`dataofsell`='$dos',`noofdays`='$nod',`dateofpay`='$paymentdate',`soldvalue`='$soldval',`commis`='$commislbl',`shareval`='$sharelbl',`netvalue`='$netvalLbl',`picture`='$fileName',`paidval`='$paidVal',`balancepay`='$balancepayable',`paystatus`='$paystatus' WHERE `id`= '$idval'";
                if ($connection->query($updateQuery) === TRUE) {
                    echo 'Data Update Success';
                ?>
                    <script>
                        $.confirm({
                            title: 'Success',
                            content: 'Data has been updated successfully',
                            autoClose: 'Confirm|2000',
                            buttons: {
                                Confirm: function() {
                                    window.location.replace("dashboardA.php");
                                }
                            }
                        });
                    </script>
                <?php
                } else {
                    echo 'Error';
                ?>
                    <script>
                        $.alert({
                            title: 'Whoops!',
                            content: 'Could process your request. Please Try Again!',
                        });
                    </script>
                <?php
                }
            } else {
                $fileName = $ifimgavailable;
                $updateQuery = "UPDATE `masterdata` SET `invNo`='$invNoert', `description`='$itemdesc',`nat_heat`='$norh',`carrat`='$carrat',`owner`='$owner',`buyer`='$buyer',`email`='$emailOwners',`phone`='$phoneBuyer',`dataofsell`='$dos',`noofdays`='$nod',`dateofpay`='$paymentdate',`soldvalue`='$soldval',`commis`='$commislbl',`shareval`='$sharelbl',`netvalue`='$netvalLbl',`picture`='$fileName',`paidval`='$paidVal',`balancepay`='$balancepayable',`paystatus`='$paystatus' WHERE `id`= '$idval'";
                if ($connection->query($updateQuery) === TRUE) {
                    echo 'Data Update Success';
                ?>
                    <script>
                        $.confirm({
                            title: 'Success',
                            content: 'Data has been updated successfully',
                            autoClose: 'Confirm|2000',
                            buttons: {
                                Confirm: function() {
                                    window.location.replace("dashboardA.php");
                                }
                            }
                        });
                    </script>
                <?php
                } else {
                    echo 'Error';
                ?>
                    <script>
                        $.alert({
                            title: 'Whoops!',
                            content: 'Could process your request. Please Try Again!',
                        });
                    </script>
                    <?php
                }
            }
        } else {
            if (file_exists($targetDir . $ifimgavailable)) {
                unlink($targetDir . $ifimgavailable);
            }
            $fileName = 'newupdate' . getName($n) .  basename($_FILES['itempic']['name']);
            $targetFilePath =  'erotuploads/' . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowTypes = array("jpg", "png", "jpeg");
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["itempic"]["tmp_name"], $targetFilePath)) {
                    $updateQuery = "UPDATE `masterdata` SET `invNo`='$invNoert', `description`='$itemdesc',`nat_heat`='$norh',`carrat`='$carrat',`owner`='$owner',`buyer`='$buyer',`email`='$emailOwners',`phone`='$phoneBuyer',`dataofsell`='$dos',`noofdays`='$nod',`dateofpay`='$paymentdate',`soldvalue`='$soldval',`commis`='$commislbl',`shareval`='$sharelbl',`netvalue`='$netvalLbl',`picture`='$fileName',`paidval`='$paidVal',`balancepay`='$balancepayable',`paystatus`='$paystatus' WHERE `id`= '$idval'";
                    if ($connection->query($updateQuery) === TRUE) {
                    ?>
                        <script>
                            $.confirm({
                                title: 'Success',
                                content: 'Data has been updated successfully',
                                autoClose: 'Confirm|2000',
                                buttons: {
                                    Confirm: function() {
                                        window.location.replace("dashboardA.php");
                                    }
                                }
                            });
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            $.alert({
                                title: 'Whoops!',
                                content: 'Could process your request. Please Try Again!',
                            });
                        </script>
    <?php
                    }
                } else {
                    echo 'Upload Failed';
                }
            } else {
                echo 'Not Allowed Type';
            }
        }
    }
    ?>

    <?php
    if (isset($_POST['updateOwnRec'])) {
        $ownupdateid = $_POST['updateOwnRec'];
        $fetchowndata = "SELECT * FROM `masterdata` WHERE `id`='$ownupdateid'";
        $fetchowndatares = $connection->query($fetchowndata);
        if ($fetchowndatares->num_rows > 0) {
            while ($ownrow = $fetchowndatares->fetch_assoc()) {
    ?>
                <div class="container">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="invNoOwn" id="invoiceLblOwn" placeholder="Invoice Number" value="<?php echo $ownrow['invNo'] ?>">
                                    <label for="invoiceLblOwn">Invoice Number</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <?php
                                if (empty($ownrow['picture']) || $ownrow['picture'] == null || $ownrow['picture'] == 'N/A') {
                                ?>
                                    <div class="alert alert-danger" role="alert">
                                        No images available. If required please upload an image
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <img src="ownuploads/<?php echo $ownrow['picture'] ?>" alt="Gemstone Picture" class="image image-fluid w-100">
                                <?php
                                }
                                ?>
                            </div>
                            <div class="col-md-6 mb-1">
                                <input type="hidden" name="ifimgavailable" value="<?php echo $ownrow['picture']; ?>">
                                <input type="file" name="itempic" class="form-control">
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <textarea class="form-control" name="itemdesc" placeholder="Item Description" id="floatingTextarea3" style="height: 100px"><?php echo $ownrow['description'] ?></textarea>
                                    <label for="floatingTextarea3">Item Description</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <select class="form-select" name="norh" id="floatingSelect" aria-label="Floating label select example">
                                        <option value="<?php echo $ownrow['nat_heat'] ?>" selected><?php echo $ownrow['nat_heat'] ?></option>
                                        <option value="Natural">Natural</option>
                                        <option value="Heat">Heat</option>
                                    </select>
                                    <label for="floatingSelect">Natural / Heat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="carrat" id="carratlabel" placeholder="Carrat" value="<?php echo $ownrow['carrat'] ?>">
                                    <label for="carratlabel">Carrat</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="purchvalue" id="purchaseValue" placeholder="Purchase Value" value="<?php echo $ownrow['purcvalue'] ?>">
                                    <label for="purchaseValue">Purchase Value</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="buyer" id="buyerLabel" placeholder="Buyer" value="<?php echo $ownrow['buyer'] ?>">
                                    <label for="buyerLabel">Buyer</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" name="emailOwners" class="form-control" id="emailOwner" placeholder="name@example.com" value="<?php echo $ownrow['email'] ?>">
                                    <label for="emailOwner">Buyer Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="number" name="phoneBuyer" class="form-control" id="phoneBuyer" placeholder="Buyer Mobile" value="<?php echo $ownrow['phone'] ?>">
                                    <label for="phoneBuyer">Buyer Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Date Of Sell</span>
                                    <input type="date" name="dos" class="form-control" id="floatingInputGroup1" placeholder="Date Of Sell" value="<?php echo $ownrow['dataofsell'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" name="nod" class="form-control" id="nodLabel" placeholder="No of Days" value="<?php echo $ownrow['noofdays'] ?>">
                                    <label for="nodLabel">No of Days</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="soldval" id="soldvalLabel" placeholder="Sold Value" value="<?php echo $ownrow['soldvalue'] ?>">
                                    <label for="soldvalLabel">Sold Value</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="paidVal" id="paidVal" placeholder="Paid Amount" value="<?php echo $ownrow['paidval'] ?>">
                                    <label for="paidVal">Paid Amount</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="button" onclick="Location.href='dashboardA.php'" class="btn btn-secondary w-100">Cancel</button>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="idval" value="<?php echo $ownrow['id']; ?>">
                                <button type="submit" name="ownRecUpdateBtn" class="btn btn-success w-100">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            header("Location.href='dashbaordB.php'");
        }
    }

    if (isset($_POST['ownRecUpdateBtn'])) {

        $invNoOwn = $_POST['invNoOwn'];
        $idval = $_POST['idval'];
        $ifimgavailable = $_POST['ifimgavailable'];
        $targetDir = "ownuploads/";
        $emailOwners = $_POST['emailOwners'];
        $phoneBuyer = $_POST['phoneBuyer'];
        $itemdesc = $_POST['itemdesc'];
        $norh = $_POST['norh'];
        $carrat = $_POST['carrat'];
        $purchvalue = $_POST['purchvalue'];
        $buyer = $_POST['buyer'];
        $dos = $_POST['dos'];
        $nod = $_POST['nod'];
        $soldval = $_POST['soldval'];
        $paidVal = $_POST['paidVal'];
        $balancepayable = $soldval - $paidVal;
        $paymentdate = date('Y-m-d', strtotime($dos . ' + ' . $nod . 'days'));
        $netvalLbl = $soldval;
        $fileName = $ifimgavailable;
        $paystatus = '';
        $own = 'own';

        if ($balancepayable === 0) {
            $paystatus = 'Paid';
        } else {
            $paystatus = 'Pending';
        }

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
        $updateQuerystring = "UPDATE `masterdata` SET `invNo`='$invNoOwn', `description`='$itemdesc',`nat_heat`='$norh',`carrat`='$carrat',`purcvalue`='$purchvalue',`buyer`='$buyer',`email`='$emailOwners',`phone`='$phoneBuyer',`dataofsell`='$dos',`noofdays`='$nod',`dateofpay`='$paymentdate',`soldvalue`='$soldval',`netvalue`='$netvalLbl',`picture`='$fileName',`paidval`='$paidVal',`balancepay`='$balancepayable',`paystatus`='$paystatus' WHERE `id`= '$idval'";

        if (empty($_FILES['itempic']['name'])) {
            if (empty($ifimgavailable) || $ifimgavailable == 'N/A') {
                $fileName = 'N/A';
                $updateQuery = $updateQuerystring;
                if ($connection->query($updateQuery) === TRUE) {
                    echo 'Data Update Success';
                ?>
                    <script>
                        $.confirm({
                            title: 'Success',
                            content: 'Data has been updated successfully',
                            autoClose: 'Confirm|2000',
                            buttons: {
                                Confirm: function() {
                                    window.location.replace("dashboardB.php");
                                }
                            }
                        });
                    </script>
                <?php
                } else {
                    echo 'Error';
                ?>
                    <script>
                        $.alert({
                            title: 'Whoops!',
                            content: 'Could process your request. Please Try Again!',
                        });
                    </script>
                <?php
                }
            } else {
                $fileName = $ifimgavailable;
                $updateQuery = $updateQuerystring;
                if ($connection->query($updateQuery) === TRUE) {
                ?>
                    <script>
                        $.confirm({
                            title: 'Success',
                            content: 'Data has been updated successfully',
                            autoClose: 'Confirm|2000',
                            buttons: {
                                Confirm: function() {
                                    window.location.replace("dashboardB.php");
                                }
                            }
                        });
                    </script>
                <?php
                } else {
                    echo 'Error';
                ?>
                    <script>
                        $.alert({
                            title: 'Whoops!',
                            content: 'Could process your request. Please Try Again!',
                        });
                    </script>
                    <?php
                }
            }
        } else {
            if (file_exists($targetDir . $ifimgavailable)) {
                unlink($targetDir . $ifimgavailable);
            }
            $fileName = 'newupdate' . getName($n) .  basename($_FILES['itempic']['name']);
            $targetFilePath =  $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowTypes = array("jpg", "png", "jpeg");
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["itempic"]["tmp_name"], $targetFilePath)) {
                    $updateQuery = "UPDATE `masterdata` SET `invNo`='$invNoOwn', `description`='$itemdesc',`nat_heat`='$norh',`carrat`='$carrat',`purcvalue`='$purchvalue',`buyer`='$buyer',`email`='$emailOwners',`phone`='$phoneBuyer',`dataofsell`='$dos',`noofdays`='$nod',`dateofpay`='$paymentdate',`soldvalue`='$soldval',`netvalue`='$netvalLbl',`picture`='$fileName',`paidval`='$paidVal',`balancepay`='$balancepayable',`paystatus`='$paystatus' WHERE `id`= '$idval'";
                    if ($connection->query($updateQuery) === TRUE) {
                    ?>
                        <script>
                            $.confirm({
                                title: 'Success',
                                content: 'Data has been updated successfully',
                                autoClose: 'Confirm|2000',
                                buttons: {
                                    Confirm: function() {
                                        window.location.replace("dashboardB.php");
                                    }
                                }
                            });
                        </script>
                    <?php
                    } else {
                        echo $connection->error;
                    ?>
                        <script>
                            $.alert({
                                title: 'Whoops!',
                                content: 'Could process your request. Please Try Again!',
                            });
                        </script>
    <?php
                    }
                } else {
                    echo 'Upload Failed';
                }
            } else {
                echo 'Not Allowed Type';
            }
        }
    }

    ?>
</body>

</html>