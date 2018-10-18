<?php
   require('config.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //houseName
        if (array_key_exists('houseName', $_POST)) {
            $hName = mysqli_real_escape_string($conn, $_POST['houseName']);
            $sql = "select * from House where House_ID=" . $_SESSION['home'];
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

            if ($row['House_Name'] != $hName) {
                
                if ($_SESSION['gID'] == 1) {
                    $sql = "update House set House_Name='" . $hName . "' where House_ID=" . $_SESSION['home'];
                    $result = mysqli_query($conn,$sql);

                    if ($result === false) {
                        if (isset($_SESSION['houseSetMsg'])) {
                            $_SESSION['houseSetMsg'] .= "<br>Could not update house name.";
                        } else {
                            $_SESSION['houseSetMsg'] = "Could not update house name.";
                        }
                    }
                } else { 

                    if (isset($_SESSION['houseSetMsg'])) {
                        $_SESSION['houseSetMsg'] .= "<br>Only admins can change the house name.";
                    } else {
                        $_SESSION['houseSetMsg'] = "Only admins can change the house name.";
                    }
                }
            }
        }

        //updateRoomNames
        if (array_key_exists('updateRooms', $_POST)) {
            $sql2 = "select * from Room where House_ID=" . $_SESSION['home'];
            $result2 = mysqli_query($conn,$sql2);
        
            while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {

                if (array_key_exists($row2['Room_ID'], $_POST['updateRooms'])) {
                    $rName = mysqli_real_escape_string($conn, $_POST['updateRooms'][$row2['Room_ID']][0]);

                    if ($rName != $row2['Room_Name']) {

                        if ($_SESSION['gID'] == 1 or ($_SESSION['gID'] == $row2['Room_gID'] and $_SESSION['gID'] != 2)) {
                            $sql3 = "update Room set Room_Name='" . $rName . "' where Room_ID=" . $row2['Room_ID'];
                            $result3 = mysqli_query($conn,$sql3);

                            if (result3 === false) {
                                
                                if (isset($_SESSION['houseSetMsg'])) {
                                    $_SESSION['houseSetMsg'] .= "<br>Could not change [" . $row2['Room_Name'] . "]'s name.";
                                } else {
                                    $_SESSION['houseSetMsg'] = "Could not change [" . $row2['Room_Name'] . "]'s name.";
                                }
                            }
                        } else {
                            
                            if (isset($_SESSION['houseSetMsg'])) {
                                $_SESSION['houseSetMsg'] .= "<br>You cannot change [" . $row2['Room_Name'] . "]'s name.";
                            } else {
                                $_SESSION['houseSetMsg'] = "You cannot change [" . $row2['Room_Name'] . "]'s name.";
                            }
                        }
                    }  
                }
            }
        }
        
        //deleteRooms
        if (array_key_exists('delRooms', $_POST)) {
            
            foreach ($_POST['delRooms'] as $val) {
                $val2 = mysqli_real_escape_string($conn, $val);
                
                $sql4 = "select * from Room where Room_ID=" . $val2;
                $result4 = mysqli_query($conn,$sql4);
                $row4 = mysqli_fetch_array($result4,MYSQLI_ASSOC);
                
                if ($_SESSION['gID'] == 1) {
                    $sql5 = "delete from Room where Room_ID=" . $val2;
                    $result5 = mysqli_query($conn,$sql5);

                    if ($result5 === false) {
                        if (isset($_SESSION['houseSetMsg'])) {
                            $_SESSION['houseSetMsg'] .= "<br>Could not delete [" . $row4['Room_Name'] . "].";
                        } else {
                            $_SESSION['houseSetMsg'] = "Could not delete [" . $row4['Room_Name'] . "].";
                        }
                    }
                } else {
                    if (isset($_SESSION['houseSetMsg'])) {
                        $_SESSION['houseSetMsg'] .= "<br>You cannot delete rooms.";
                    } else {
                        $_SESSION['houseSetMsg'] = "You cannot delete rooms.";
                    }
                    break;
                }
            }
        }
        
        //createRooms
        if (array_key_exists('newRooms', $_POST)) {
            
            if ($_SESSION['gID'] == 1) {
                foreach ($_POST['newRooms'] as $val3) {
                    $val4 = mysqli_real_escape_string($conn, $val3);
                    $sql6 = "insert into Room(House_ID, Room_Name) values (" . $_SESSION['home'] . ", '" . $val4 . "')";
                    $result6 = mysqli_query($conn,$sql6);

                    if ($result6 === false) {
                        if (isset($_SESSION['houseSetMsg'])) {
                            $_SESSION['houseSetMsg'] .= "<br>Could not create [" . $val4 ."].";
                        } else {
                            $_SESSION['houseSetMsg'] = "Could not create [" . $val4 ."].";
                        }
                        break;
                    }
                }
            } else {
                if (isset($_SESSION['houseSetMsg'])) {
                    $_SESSION['houseSetMsg'] .= "<br>You cannot create rooms.";
                } else {
                    $_SESSION['houseSetMsg'] = "You cannot create rooms.";
                }
            }
        }
                
        header('Location: house-settings.php');
    }
?>