<?php
    require('config.php');
    
    if($_SESSION["guest"] == true) {
        $_SESSION['loginMsg'] = "Please login first.";
        header("Location: login.php");
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>HARP</title>
	<!-- AJAX & jQuery CDN, must go before Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="CSS/main.css" type="text/css" rel="stylesheet">
        <link rel="icon" type="image/png" href="Images/harp_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <script>
        function fnSwitchClick(clicked_id) {
            var change = document.getElementById(clicked_id);
            if (change.classList.contains('fa-unlock') || change.classList.contains('fa-lock'))
            {
                if (change.classList.contains('fa-lock'))
                {
                    change.classList.remove('fa-lock');
                    change.classList.add('fa-unlock');
                    change.classList.remove('btn-off');
                    change.classList.add('btn-on');
                }
                else
                {
                    change.classList.remove('fa-unlock');
                    change.classList.add('fa-lock');   
                    change.classList.remove('btn-on');
                    change.classList.add('btn-off');
                }
            }
            else
            {
                if (change.innerHTML == "On")
                {
                    change.innerHTML = "Off";

			//Sends the button ID and (minus first character) and 0 to PHP
			$.post("saveDatabase.php",
			    {
				id: change.id.substring(1),
				state: '0',
			    });
                }
                else {
                    change.innerHTML = "On";

			//Sends the button ID (minus first character) and 1 to PHP
			$.post("saveDatabase.php",
			    {
				id: change.id.substring(1),
				state: '1',
			    });
                }

                if (change.classList.contains('btn-on'))
                {
                    change.classList.remove('btn-on');
                    change.classList.add('btn-off');
                }
                else
                {
                    change.classList.remove('btn-off');
                    change.classList.add('btn-on');   
                }
            }
        }
        
        function fnComponentSettingsRedirect(clicked_id) {
            window.location.href='/component-settings.php?component-id=' + clicked_id
        }

        function fnReturnToLogin() {
            window.location.href='/login.html';
        }

        function phpLogout() {
            if(confirm('Are you sure you want to log out? Logging out will automatically turn off all appliances.'))
                document.location.href = 'logoutScript.php';
        }
        
        $(function() {
            $('#roomList').change(function(){
                var room = $(this).val();
                room = room.replace(/\s/g,'');
                if($(this).val() == "All Rooms") {
                    $('.rooms').show();
                }
                else {
                    $('.rooms').hide();
                    $('.rooms').each(function(i, obj) {
                        if(this.id == room) {
                            $('.' + this.id).show();
                        }
                    });
                }
            });
        });

	//Function to check button states and update	
	function checkButtons() {
		//Find all buttons, store
		var className = document.getElementsByClassName('btn-component-switch');
		    var classnameCount = className.length;
		    var IdStore = new Array();
		    for(var j = 0; j < classnameCount; j++){
			IdStore.push(className[j].id);
		    }
			//For buttons with prefix "b", store just number
			var idArr = new Array();			
			var arrayLength = IdStore.length;
			for (var i = 0; i < arrayLength; i++) {			    
				if (IdStore[i].substring(0,1) == "b"){
					idArr.push(IdStore[i]);
				}
			}
			

			//Use idArr to check database
			var arrayLength2 = idArr.length;
			for (var i = 0; i < arrayLength2; i++){					
				//"let" is better than "for" for AJAX						
				let tempButton = idArr[i];	
				$.post(
					"readButton.php",
					{ id: (tempButton.substring(1)) },
					function(response) {
						if (response.state == '1'){							
							document.getElementById(tempButton).innerHTML = "On";
                    					document.getElementById(tempButton).classList.remove('btn-off');
                    					document.getElementById(tempButton).classList.add('btn-on');
						}
						else if (response.state == '0'){
							document.getElementById(tempButton).innerHTML = "Off";
                    					document.getElementById(tempButton).classList.remove('btn-on');
                    					document.getElementById(tempButton).classList.add('btn-off');
						}

					}, 'json'
				);
			}

	}

	//Execute checkButtons every 2 seconds
	window.setInterval(function(){
	  	checkButtons();
	}, 2000);

    </script>

            <body class="login-body">
                <div class="main-page-container">
                    <?php
                        $hID = $_SESSION['home'];
                        $sql = "select * from House where House_ID='$hID'";
		                $result = mysqli_query($conn,$sql);
                        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            
                        $hName = $row['House_Name'];
            
                        $count = mysqli_num_rows($result);
                    ?>
                    <div>
                        <?php
                            if (!isset($_SESSION['indexMsg'])) {
                                echo '<center><label id="accountErrorText" class="lbl-create-account-hidden"></label></center>';
                            } else { 
                                echo '<center><label id="accountErrorText" class="lbl-create-account-visible">' . $_SESSION['indexMsg'] . '</label></center>';
                                unset($_SESSION['indexMsg']);
                            }
                        ?>
                        <h1 class="text-center h1-main-page"><?php echo $hName; ?></h1>
                        <button class="fa fa-sign-out-alt btn-sign-out" onclick="phpLogout()"></button>
                        <button class="fa fa-cog btn-sign-out btn-cog" onclick="window.location.href='house-settings.php'"></button>
                    </div>
                    <div class="div-devices">
                        <div style="display: inline-block">
                            <h4 class="h4-devices">New Appliance</h4>
                            <button class="btn-new-component" onclick="window.location.href='/new-component.html?house-id=1'">+</button>
                        </div>
                        <div style="display: inline-block">
                            <select id="roomList" class="selects">
                                <?php
                                $sql2 = "select * from Room where House_ID='$hID'";
                                $result2 = mysqli_query($conn,$sql2);
                                
                                echo '<option value="All Rooms">All Rooms</option>';
                                while($row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
                                    $rID = $row2['Room_ID'];
                                    $rN = $row2['Room_Name'];
                                    echo '<option value="' . $rN . '">' . $rN . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

            <?php
                $sql3 = "SELECT Addon.Addon_Name, Addon.Addon_Description, Addon.Addon_ID, A.Room_Name, Addon.Addon_State 
                            FROM Addon 
                            INNER JOIN 
                            (select * from Room where House_ID=1) as A
                            ON A.Room_ID=Addon.Addon_Room_ID;";
                $result3 = mysqli_query($conn,$sql3);
                $i = -1;
                while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)) {
                    $aN = $row3['Addon_Name'];
                    $aD = $row3['Addon_Description'];
                    $aID = $row3['Addon_ID'];
                    $rN = $row3['Room_Name'];
                    $aS = $row3['Addon_State'];
                    $strippedrN=preg_replace('/\s+/', '', $rN);
			//Sets variables based on Addon_State
			if ($aS == '1') {
	    			$buttonClass = "btn-component-switch btn-on";
				$buttonText = "On";
			} else{
	    			$buttonClass = "btn-component-switch btn-off";
				$buttonText = "Off";
			}
                    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 rooms ' . $strippedrN .'" id="' . $strippedrN . '">
                            <div class="component-card">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <p class="p-component-main">' . $aN . '</p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <button class="'. $buttonClass . '" id="b' . $aID . '" onclick="fnSwitchClick(this.id)">'. $buttonText . '</button>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <p class="p-component-label"><b>Room:</b> ' . $rN . '</p>
                                    <p class="p-component-label"><b>Description:</b> ' . $aD . '</p>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <button class="btn-component-switch fa fa-cog" id="c' . $aID . '" onclick="fnComponentSettingsRedirect(this.id)"></button>
                                </div>
                            </div>
                          </div>';
                }
            ?>
        </div>
    </body>    
</html>
