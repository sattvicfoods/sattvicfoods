<div class="main-col-inner">
	<div id="messages"></div>
<?php if( isset( $_POST['Bluedart_information_licence_key'] ) ){
			 
            $Bluedart_information_enable= update_option( 'Bluedart_information_enable', serialize($_POST['Bluedart_information_enable']) );
            $Bluedart_information_select_mode= update_option( 'Bluedart_information_select_mode', serialize($_POST['Bluedart_information_select_mode']) );
            $Bluedart_information_licence_key= update_option( 'Bluedart_information_licence_key', serialize($_POST['Bluedart_information_licence_key']) );
            $Bluedart_information_loginid= update_option( 'Bluedart_information_loginid', serialize($_POST['Bluedart_information_loginid']) );
            $Bluedart_information_email= update_option( 'Bluedart_information_email', serialize($_POST['Bluedart_information_email']) );
            $Bluedart_information_store_name= update_option( 'Bluedart_information_store_name', serialize($_POST['Bluedart_information_store_name']) );
            $Bluedart_information_phone= update_option( 'Bluedart_information_phone', serialize($_POST['Bluedart_information_phone']) );
            $Bluedart_information_store_address= update_option( 'Bluedart_information_store_address', serialize($_POST['Bluedart_information_store_address']) );	
            $Bluedart_information_pincode= update_option( 'Bluedart_information_pincode', serialize($_POST['Bluedart_information_pincode']) ); 
            $Bluedart_information_customercode= update_option( 'Bluedart_information_customercode', serialize($_POST['Bluedart_information_customercode']) ); 
            $Bluedart_information_vandercode= update_option( 'Bluedart_information_vandercode', serialize($_POST['Bluedart_information_vandercode']) ); 
            $Bluedart_information_originarea= update_option( 'Bluedart_information_originarea', serialize($_POST['Bluedart_information_originarea']) ); 
            $Bluedart_information_tin_no= update_option( 'Bluedart_information_tin_no', serialize($_POST['Bluedart_information_tin_no']) ); 
            
             
            if($_FILES["Bluedart_information_logo"]["name"] != "" ){
                
                $upload_dir = wp_upload_dir();
                $target_dir =$upload_dir['basedir'];
				$target_file = $target_dir.'/'.basename($_FILES["Bluedart_information_logo"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
					
				$check = getimagesize($_FILES["Bluedart_information_logo"]["tmp_name"]);
				if($check !== false) {
				
							$uploadOk = 1;
									
						} else {
							
							echo "File is not an image.";
							$uploadOk = 0;
					
						}
						
				// Check if file already exists
				if (file_exists($target_file)) {
						
							echo "Sorry, file already exists.";
							$uploadOk = 0;
				
						}
				
						// Check file size
				if ($_FILES["Bluedart_information_logo"]["size"] > 500000) {
				
							echo "Sorry, your file is too large.";
							$uploadOk = 0;
									
						}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
							echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
							$uploadOk = 0;
									
						}
				
						// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
							
							echo "Sorry, your file was not uploaded.";
				
							// if everything is ok, try to upload file
				
						} else {
				
							if (move_uploaded_file($_FILES["Bluedart_information_logo"]["tmp_name"], $target_file)) {
					//echo "The file ". basename( $_FILES["Bluedart_information_logo"]["name"]). " has been uploaded.";
							} else {
					echo "Sorry, there was an error uploading your file.";
							}
									 
				}
						
				$Bluedart_information_logo= update_option( 'Bluedart_information_logo', serialize($_FILES["Bluedart_information_logo"]["name"]) ); 
			}
            
        }
  ?>

        <form method="post" action="options-general.php?page=Blue-Dart-Shipment" enctype="multipart/form-data" id="bluedart_shipping_form"> 						
             <div id="messages1"></div>
            <div class="content-header">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="width: 64%;text-align: center;">
                                <h3>Bluedart Information</h3>
                            </td>
                            <td class="form-buttons"></td>					
                        </tr>
                    </tbody>
                </table>
            </div> 

            <table class="form-list" cellspacing="0">
		<colgroup class="label"></colgroup>
		<colgroup class="value"></colgroup>
		<colgroup class="scope-label"></colgroup>
		<colgroup class=""></colgroup>
                <tbody>
                    <tr id="row_Bluedart_information_enable">
                        <td class="label"><label for="Bluedart_information_enable"> Enable</label></td>
			<td class="value">
                            <?php $enable=unserialize(get_option('Bluedart_information_enable'));?>
                            <select class=" select" name="Bluedart_information_enable" id="">
								
				<option value="1" <?php if($enable == 1) echo "selected="."select";?> >Yes</option>
				<option value="0" <?php if($enable == 0) echo "selected="."select";?>>No</option>
                            
                            </select>
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_select_mode">
                        <td class="label">
                            <label for="Bluedart_information_select_mode"> Select Mode</label>
			</td>
			<td class="value">
					<?php $mode=unserialize(get_option('Bluedart_information_select_mode'));?>
					<select class=" select" name="Bluedart_information_select_mode" id="Bluedart_information_select_mode">
					<option <?php if($mode == 1) echo "selected="."select";?> value="1">Sandbox</option>
					<option <?php if($mode == 2) echo "selected="."select";?> value="2">Live</option>
		
					</select>
			</td>
                    </tr>
                    <tr id="row_bluedart_Bluedart_information_licence_key">
			<td class="label"><label for="Bluedart_information_licence_key"> Licence Key</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_licence_key'));?>" name="Bluedart_information_licence_key" id="Bluedart_information_licence_key">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_loginid">
			<td class="label"><label for="Bluedart_information_loginid"> LoginID</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_loginid'));?>" name="Bluedart_information_loginid" id="Bluedart_information_loginid">
                        </td>
                    </tr>
                    <tr id="row_Bluedart_information_email">
			<td class="label"><label for="Bluedart_information_email"> Email Id (From email send)</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_email'));?>" name="Bluedart_information_email" id="Bluedart_information_email">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_store_name">
			<td class="label"><label for="Bluedart_information_store_name"> Store Name</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_store_name'));?>" name="Bluedart_information_store_name" id="Bluedart_information_store_name">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_phone">
			<td class="label"><label for="Bluedart_information_phone"> India's Contact Telephone</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_phone'));?>" name="Bluedart_information_phone" id="Bluedart_information_phone">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_store_address">
			<td class="label"><label for="Bluedart_information_store_address"> Store Contact Address</label></td>
			<td class="value">
                            <textarea cols="15" rows="6" class=" textarea" name="Bluedart_information_store_address" id="Bluedart_information_store_address"><?php echo unserialize(get_option('Bluedart_information_store_address'));?></textarea>
                        </td>
                    </tr>
                    <tr id="row_Bluedart_information_pincode">
                        <td class="label"><label for="Bluedart_information_pincode"> PinCode</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_pincode'));?>" name="Bluedart_information_pincode" id="Bluedart_information_pincode">
									  </td>
                    </tr>
                    <tr id="row_Bluedart_information_customercode">
			<td class="label"><label for="Bluedart_information_customercode"> Customer code</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_customercode'));?>" name="Bluedart_information_customercode" id="Bluedart_information_customercode">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_vandercode">
			<td class="label"><label for="Bluedart_information_vandercode"> Vander code</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_vandercode'));?>" name="Bluedart_information_vandercode" id="Bluedart_information_vandercode">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_originarea">
			<td class="label"><label for="Bluedart_information_originarea"> Origin area</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_originarea'));?>" name="Bluedart_information_originarea" id="Bluedart_information_originarea">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_tin_no">
                        <td class="label"><label for="Bluedart_information_tin_no"> TIN No.</label></td>
			<td class="value"><input type="text" class=" input-text" value="<?php echo unserialize(get_option('Bluedart_information_tin_no'));?>" name="Bluedart_information_tin_no" id="Bluedart_information_tin_no">
			</td>
                    </tr>
                    <tr id="row_Bluedart_information_logo">
			<td class="label"><label for="Bluedart_information_logo"> Logo for PDF</label></td>
			<td class="value">
                            <?php $logo_src=home_url().'/wp-content/uploads/'.unserialize(get_option( 'Bluedart_information_logo')); ?>
                            <a hrfe="<?php echo $logo_src; ?>"> 
				<img src="<?php echo $logo_src; ?>" style="width:50px;height:20px;"/> </a>
				<input type="file" class="input-file" value="" name="Bluedart_information_logo" id="Bluedart_information_logo">
			</td>
             </tr><tr><td></td><td></td>
              <tr>
                        <td></td>
                       <td class="form-buttons"><input type="button" value="Save Config" name="info_save" id="bluedart_info_save" onclick="submitForm();" ></td>
                    </tr>        
                </tbody>
            </table>
	</form>
    </div>
