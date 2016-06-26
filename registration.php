<?php
/**
 * Registration page.
 * @author Vlad Gorbich
 */

require_once 'class.user.php';
date_default_timezone_set('America/Chicago');

$form = true;
$reg_user = new USER();
$title = $fname = $mname = $lname = $email = $phone = $webpage = $organization = $department = '';
$address = $city = $state = $zip = '';

if(isset($_POST['btn-register'])) {
	$title = trim($_POST['selecttitle']); 
	$fname = trim($_POST['txtfname']);
	$mname = trim($_POST['txtmname']);
	$lname = trim($_POST['txtlname']);
	$email = trim($_POST['txtemail']);
	$phone = trim($_POST['txtphone']);
	$webpage = trim($_POST['txtwebpage']);
	$organization = trim($_POST['txtorganization']);
	$department = trim($_POST['txtdepartment']);
	$address = trim($_POST['txtaddress']);
	$city = trim($_POST['txtcity']);
	$state = trim($_POST['txtstate']);
	$zip = trim($_POST['txtzip']);
	$country = trim($_POST['selectcountry']);
	$accompany = trim($_POST['txtaccompany']);
	$presentation = isset($_POST['presentation']) && $_POST['presentation']  ? "1" : "0";
	$student = isset($_POST['student']) && $_POST['student']  ? "1" : "0";
	$paper = isset($_POST['paper']) && $_POST['paper']  ? "1" : "0";
	$code = md5(uniqid(rand()));
	$time = date("Y-m-d H:i:s");
	
	// VALIDATION
	// Check if email is already taken
	$stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
	$stmt->execute(array(":email_id"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	if($stmt->rowCount() > 0) {
		$msg = "<div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Email allready exists. Please try another one.</strong>
			    </div>";
	// Check if the required fields are empty
} elseif(($fname == '') || ($lname == '') || ($email == '') || ($address == '') || ($city == '') || ($state == '') || ($zip == '')) {
		$msg = "<div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Required fields are empty</strong>
			    </div>";
	// Check if email is valid
	} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$msg = "<div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Email is invalid</strong>
			    </div>";	
	// Check if variable is too long
	} elseif((strlen($fname) > 50) || (strlen($mname) > 50) || (strlen($lname) > 50) || (strlen($email) > 50) || (strlen($phone) > 50) || (strlen($webpage) > 50) || (strlen($organization) > 50) || (strlen($department) > 50) || (strlen($address) > 50) || (strlen($city) > 50) || (strlen($state) > 50) || (strlen($zip) > 50) ) {
	    $msg = "<div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>One or more fields have too many characters</strong>
			    </div>";
	} else {
		if ($reg_user->register($title,$fname,$mname,$lname,$email,$phone,$webpage,$organization,$department,$address,$city,$state,$zip,$country,$accompany,$presentation,$student,$paper,$code,$time)) {			
			$id = $reg_user->lasdID();		
			$key = base64_encode($id);
			$id = $key;			
			$message = "Hello $fname $lname,
						Welcome to NGC 2017
						To complete your registration  please click the following link
						<a href='http://site.com/verify.php?id=$id&code=$code'>Click HERE to Activate</a>
						";						
			$subject = "Registration";						
			$reg_user->send_mail($email, $message, $subject);	
			$msg = "<div class='alert alert-success'>
				<strong>Almost there!</strong>  We've sent an email to $email.
                    		Please click on the link in the email to confirm your registration. 
			  	</div>";
			$form = false;
		} else {
			echo "Query could not execute. Try again later.";
		}		
	}
}
?>

<!-- bootstrap stuff goes here --->

<div class="col-sm-10">
  <h2>Registration</h2>
  <p>We thank you for your interest in participating at our conference! Fill in our online Conference 
  Registration Form below to receive your badge number and the schedule of the entire event.</p>
  <div class="col-sm-1">&nbsp;</div>
  <div class="col-sm-8"> 
  <div class="form-box">  
  <?php if(isset($msg)) echo $msg;  
  if ($form) { ?>
      <form method="post">
		<!--<small class="text-muted">&#42; indicates required</small>-->
	    <fieldset class="form-group row required">
		  <div class="col-sm-4">
		    <label for="selecttitle"  class="form-control-label">Attendee's Name</label>
		    <p><small class="text-muted">Will appear on conference badge.</small></p>
		  </div>
		  <div class="col-sm-8">
		    <select name="selecttitle" id="selecttitle" class="form-control">
		    <option value="Dr.">Dr.</option>
		    <option value="Prof.">Prof.</option>
		    <option value="Doz.">Doz.</option>
		    <option value="Mrs.">Mrs.</option>
		    <option value="Ms.">Ms.</option>
		    <option value="Mr.">Mr.</option>
		    </select>
			<input type="text" class="form-control" placeholder="First" value="<?php echo $fname ?>" name="txtfname" id="txtfname" required />
		    <input type="text" class="form-control" placeholder="Middle" value="<?php echo $mname ?>" name="txtmname" id="txtfname" />		  
		    <input type="text" class="form-control" placeholder="Last" value="<?php echo $lname ?>" name="txtlname" id="txtlname" required />
		  </div>
		</fieldset>
		
        <fieldset class="form-group row">
		  <div class="col-sm-4">
		    <label for="txtorganization" class="form-control-label" value="<?php echo $organization ?>">Company/Organization</label>	
		  </div>
          <div class="col-sm-8">		  
		    <input type="text" class="form-control" value="<?php echo $organization ?>" placeholder="" name="txtorganization" id="txtorganization" />
		  </div>
		</fieldset>	
		
		<fieldset class="form-group row">
		  <div class="col-sm-4">
		    <label for="txtdepartment" class="form-control-label">Department</label>
          </div>			
		  <div class="col-sm-8">
		    <input type="text" class="form-control" value="<?php echo $department ?>" placeholder="" name="txtdepartment" id="txtdepartment" />
		  </div>
		</fieldset>
		
        <fieldset class="form-group row">
		  <div class="col-sm-4">		
		    <label for="txtwebpage" class="form-control-label">Personal or research group web page</label>
		  </div>
          <div class="col-sm-8">		  
		    <input type="text" class="form-control" value="<?php echo $webpage ?>" placeholder="" name="txtwebpage" id="txtwebpage" />
		  </div>
		</fieldset>
		
		<fieldset class="form-group row required">
		  <div class="col-sm-4">
		    <label for="txtemail" class="form-control-label">Email Address</label>	
          </div>			
		  <div class="col-sm-8">
            <input type="email" class="form-control" placeholder="" value="<?php echo $email ?>" name="txtemail" id="txtemail" required />
		  </div>
		</fieldset>
		
        <fieldset class="form-group row">
		  <div class="col-sm-4">		  
		    <label for="txtphone" class="form-control-label">Phone</label>	
		  </div>
		  <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="include country code" value="<?php echo $phone ?>" name="txtphone" id="txtphone" />
		  </div>
		</fieldset>
				
		<fieldset class="form-group row required">
		  <div class="col-sm-4">
		    <label for="txtaddress" class="form-control-label">Address</label>
          </div>			
		  <div class="col-sm-8">
		    <input type="text" class="form-control" placeholder="Street Address" value="<?php echo $address ?>" name="txtaddress" id="txtaddress" required />
		    <input type="text" class="form-control" placeholder="City" value="<?php echo $city ?>" name="txtcity" id="txtcity" required />		  
		    <input type="text" class="form-control" placeholder="State / Province" value="<?php echo $state ?>" name="txtstate" id="txtstate" />		  
		    <input type="text" class="form-control" placeholder="Postal Code" value="<?php echo $zip ?>" name="txtzip" id="txtzip" required />		  
		    <select class="form-control" name="selectcountry" id="selectcountry">
			<option value="United States" selected="selected">United States</option> 
			<option value="United Kingdom">United Kingdom</option> 
			<option value="Afghanistan">Afghanistan</option> 
			<option value="Albania">Albania</option> 
			<option value="Algeria">Algeria</option> 
			<option value="American Samoa">American Samoa</option> 
			<option value="Andorra">Andorra</option> 
			<option value="Angola">Angola</option> 
			<option value="Anguilla">Anguilla</option> 
			<option value="Antarctica">Antarctica</option> 
			<option value="Antigua and Barbuda">Antigua and Barbuda</option> 
			<option value="Argentina">Argentina</option> 
			<option value="Armenia">Armenia</option> 
			<option value="Aruba">Aruba</option> 
			<option value="Australia">Australia</option> 
			<option value="Austria">Austria</option> 
			<option value="Azerbaijan">Azerbaijan</option> 
			<option value="Bahamas">Bahamas</option> 
			<option value="Bahrain">Bahrain</option> 
			<option value="Bangladesh">Bangladesh</option> 
			<option value="Barbados">Barbados</option> 
			<option value="Belarus">Belarus</option> 
			<option value="Belgium">Belgium</option> 
			<option value="Belize">Belize</option> 
			<option value="Benin">Benin</option> 
			<option value="Bermuda">Bermuda</option> 
			<option value="Bhutan">Bhutan</option> 
			<option value="Bolivia">Bolivia</option> 
			<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
			<option value="Botswana">Botswana</option> 
			<option value="Bouvet Island">Bouvet Island</option> 
			<option value="Brazil">Brazil</option> 
			<option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
			<option value="Brunei Darussalam">Brunei Darussalam</option> 
			<option value="Bulgaria">Bulgaria</option> 
			<option value="Burkina Faso">Burkina Faso</option> 
			<option value="Burundi">Burundi</option> 
			<option value="Cambodia">Cambodia</option> 
			<option value="Cameroon">Cameroon</option> 
			<option value="Canada">Canada</option> 
			<option value="Cape Verde">Cape Verde</option> 
			<option value="Cayman Islands">Cayman Islands</option> 
			<option value="Central African Republic">Central African Republic</option> 
			<option value="Chad">Chad</option> 
			<option value="Chile">Chile</option> 
			<option value="China">China</option> 
			<option value="Christmas Island">Christmas Island</option> 
			<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
			<option value="Colombia">Colombia</option> 
			<option value="Comoros">Comoros</option> 
			<option value="Congo">Congo</option> 
			<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
			<option value="Cook Islands">Cook Islands</option> 
			<option value="Costa Rica">Costa Rica</option> 
			<option value="Cote D'ivoire">Cote D'ivoire</option> 
			<option value="Croatia">Croatia</option> 
			<option value="Cuba">Cuba</option> 
			<option value="Cyprus">Cyprus</option> 
			<option value="Czech Republic">Czech Republic</option> 
			<option value="Denmark">Denmark</option> 
			<option value="Djibouti">Djibouti</option> 
			<option value="Dominica">Dominica</option> 
			<option value="Dominican Republic">Dominican Republic</option> 
			<option value="Ecuador">Ecuador</option> 
			<option value="Egypt">Egypt</option> 
			<option value="El Salvador">El Salvador</option> 
			<option value="Equatorial Guinea">Equatorial Guinea</option> 
			<option value="Eritrea">Eritrea</option> 
			<option value="Estonia">Estonia</option> 
			<option value="Ethiopia">Ethiopia</option> 
			<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
			<option value="Faroe Islands">Faroe Islands</option> 
			<option value="Fiji">Fiji</option> 
			<option value="Finland">Finland</option> 
			<option value="France">France</option> 
			<option value="French Guiana">French Guiana</option> 
			<option value="French Polynesia">French Polynesia</option> 
			<option value="French Southern Territories">French Southern Territories</option> 
			<option value="Gabon">Gabon</option> 
			<option value="Gambia">Gambia</option> 
			<option value="Georgia">Georgia</option> 
			<option value="Germany">Germany</option> 
			<option value="Ghana">Ghana</option> 
			<option value="Gibraltar">Gibraltar</option> 
			<option value="Greece">Greece</option> 
			<option value="Greenland">Greenland</option> 
			<option value="Grenada">Grenada</option> 
			<option value="Guadeloupe">Guadeloupe</option> 
			<option value="Guam">Guam</option> 
			<option value="Guatemala">Guatemala</option> 
			<option value="Guinea">Guinea</option> 
			<option value="Guinea-bissau">Guinea-bissau</option> 
			<option value="Guyana">Guyana</option> 
			<option value="Haiti">Haiti</option> 
			<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
			<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
			<option value="Honduras">Honduras</option> 
			<option value="Hong Kong">Hong Kong</option> 
			<option value="Hungary">Hungary</option> 
			<option value="Iceland">Iceland</option> 
			<option value="India">India</option> 
			<option value="Indonesia">Indonesia</option> 
			<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
			<option value="Iraq">Iraq</option> 
			<option value="Ireland">Ireland</option> 
			<option value="Israel">Israel</option> 
			<option value="Italy">Italy</option> 
			<option value="Jamaica">Jamaica</option> 
			<option value="Japan">Japan</option> 
			<option value="Jordan">Jordan</option> 
			<option value="Kazakhstan">Kazakhstan</option> 
			<option value="Kenya">Kenya</option> 
			<option value="Kiribati">Kiribati</option> 
			<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
			<option value="Korea, Republic of">Korea, Republic of</option> 
			<option value="Kuwait">Kuwait</option> 
			<option value="Kyrgyzstan">Kyrgyzstan</option> 
			<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
			<option value="Latvia">Latvia</option> 
			<option value="Lebanon">Lebanon</option> 
			<option value="Lesotho">Lesotho</option> 
			<option value="Liberia">Liberia</option> 
			<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
			<option value="Liechtenstein">Liechtenstein</option> 
			<option value="Lithuania">Lithuania</option> 
			<option value="Luxembourg">Luxembourg</option> 
			<option value="Macao">Macao</option> 
			<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
			<option value="Madagascar">Madagascar</option> 
			<option value="Malawi">Malawi</option> 
			<option value="Malaysia">Malaysia</option> 
			<option value="Maldives">Maldives</option> 
			<option value="Mali">Mali</option> 
			<option value="Malta">Malta</option> 
			<option value="Marshall Islands">Marshall Islands</option> 
			<option value="Martinique">Martinique</option> 
			<option value="Mauritania">Mauritania</option> 
			<option value="Mauritius">Mauritius</option> 
			<option value="Mayotte">Mayotte</option> 
			<option value="Mexico">Mexico</option> 
			<option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
			<option value="Moldova, Republic of">Moldova, Republic of</option> 
			<option value="Monaco">Monaco</option> 
			<option value="Mongolia">Mongolia</option> 
			<option value="Montenegro">Montenegro</option>
			<option value="Montserrat">Montserrat</option> 
			<option value="Morocco">Morocco</option> 
			<option value="Mozambique">Mozambique</option> 
			<option value="Myanmar">Myanmar</option> 
			<option value="Namibia">Namibia</option> 
			<option value="Nauru">Nauru</option> 
			<option value="Nepal">Nepal</option> 
			<option value="Netherlands">Netherlands</option> 
			<option value="Netherlands Antilles">Netherlands Antilles</option> 
			<option value="New Caledonia">New Caledonia</option> 
			<option value="New Zealand">New Zealand</option> 
			<option value="Nicaragua">Nicaragua</option> 
			<option value="Niger">Niger</option> 
			<option value="Nigeria">Nigeria</option> 
			<option value="Niue">Niue</option> 
			<option value="Norfolk Island">Norfolk Island</option> 
			<option value="Northern Mariana Islands">Northern Mariana Islands</option> 
			<option value="Norway">Norway</option> 
			<option value="Oman">Oman</option> 
			<option value="Pakistan">Pakistan</option> 
			<option value="Palau">Palau</option> 
			<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
			<option value="Panama">Panama</option> 
			<option value="Papua New Guinea">Papua New Guinea</option> 
			<option value="Paraguay">Paraguay</option> 
			<option value="Peru">Peru</option> 
			<option value="Philippines">Philippines</option> 
			<option value="Pitcairn">Pitcairn</option> 
			<option value="Poland">Poland</option> 
			<option value="Portugal">Portugal</option> 
			<option value="Puerto Rico">Puerto Rico</option> 
			<option value="Qatar">Qatar</option> 
			<option value="Reunion">Reunion</option> 
			<option value="Romania">Romania</option> 
			<option value="Russian Federation">Russian Federation</option> 
			<option value="Rwanda">Rwanda</option> 
			<option value="Saint Helena">Saint Helena</option> 
			<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
			<option value="Saint Lucia">Saint Lucia</option> 
			<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
			<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
			<option value="Samoa">Samoa</option> 
			<option value="San Marino">San Marino</option> 
			<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
			<option value="Saudi Arabia">Saudi Arabia</option> 
			<option value="Senegal">Senegal</option> 
			<option value="Serbia">Serbia</option> 
			<option value="Seychelles">Seychelles</option> 
			<option value="Sierra Leone">Sierra Leone</option> 
			<option value="Singapore">Singapore</option> 
			<option value="Slovakia">Slovakia</option> 
			<option value="Slovenia">Slovenia</option> 
			<option value="Solomon Islands">Solomon Islands</option> 
			<option value="Somalia">Somalia</option> 
			<option value="South Africa">South Africa</option> 
			<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
			<option value="South Sudan">South Sudan</option> 
			<option value="Spain">Spain</option> 
			<option value="Sri Lanka">Sri Lanka</option> 
			<option value="Sudan">Sudan</option> 
			<option value="Suriname">Suriname</option> 
			<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
			<option value="Swaziland">Swaziland</option> 
			<option value="Sweden">Sweden</option> 
			<option value="Switzerland">Switzerland</option> 
			<option value="Syrian Arab Republic">Syrian Arab Republic</option> 
			<option value="Taiwan, Republic of China">Taiwan, Republic of China</option> 
			<option value="Tajikistan">Tajikistan</option> 
			<option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
			<option value="Thailand">Thailand</option> 
			<option value="Timor-leste">Timor-leste</option> 
			<option value="Togo">Togo</option> 
			<option value="Tokelau">Tokelau</option> 
			<option value="Tonga">Tonga</option> 
			<option value="Trinidad and Tobago">Trinidad and Tobago</option> 
			<option value="Tunisia">Tunisia</option> 
			<option value="Turkey">Turkey</option> 
			<option value="Turkmenistan">Turkmenistan</option> 
			<option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
			<option value="Tuvalu">Tuvalu</option> 
			<option value="Uganda">Uganda</option> 
			<option value="Ukraine">Ukraine</option> 
			<option value="United Arab Emirates">United Arab Emirates</option> 
			<option value="United Kingdom">United Kingdom</option> 
			<option value="United States">United States</option> 
			<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
			<option value="Uruguay">Uruguay</option> 
			<option value="Uzbekistan">Uzbekistan</option> 
			<option value="Vanuatu">Vanuatu</option> 
			<option value="Venezuela">Venezuela</option> 
			<option value="Viet Nam">Viet Nam</option> 
			<option value="Virgin Islands, British">Virgin Islands, British</option> 
			<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
			<option value="Wallis and Futuna">Wallis and Futuna</option> 
			<option value="Western Sahara">Western Sahara</option> 
			<option value="Yemen">Yemen</option> 
			<option value="Zambia">Zambia</option> 
			<option value="Zimbabwe">Zimbabwe</option>
		  </select>
		  </div>
		</fieldset>
		
		<fieldset class="form-group row">
		  <div class="col-sm-10">
		    <label for="txtaccompany" class="form-control-label">How many people will accompany you to the conference?</label>
		  </div>
		  <div class="col-sm-2">		  
		    <select class="form-control" name="txtaccompany" id="txtaccompany">
			<option value="0" selected="selected">0</option> 
			<option value="1">1</option> 
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>	
			</select>
		  </div>
		</fieldset>
		
		<div class="form-group row">
		  <div class="col-sm-6">
		    <label class="form-control-label">Are you planning a presentation?</label>
		  </div>
		  <div class="col-sm-6">
		    <label class="checkbox-inline">
				<input type="checkbox" id="presentation" name="presentation[]" value="yes" /> Yes
			</label>
		  </div>
		</div>
		  
		<div class="form-group row">
		  <div class="col-sm-6">
		    <label class="form-control-label">Are you a full time student?</label>
		  </div>
		  <div class="col-sm-6">
		    <label class="checkbox-inline">
				<input type="checkbox" id="student" name="student[]" value="yes" /> Yes
			</label>
		  </div>
		</div>		  

		<div class="form-group row">
		  <div class="col-sm-6">
		    <label class="form-control-label">Are you planning to submit a paper to the Conference Proceedings?</label>
		  </div>
		  <div class="col-sm-6">
		    <label class="checkbox-inline">
				<input type="checkbox" id="paper" name="paper[]" value="yes" /> Yes
			</label>
		  </div>
		</div>			

        <button class="btn btn-large btn-primary" type="submit" name="btn-register">Register</button>

      </form>
<?php } ?>
	</div><!-- End of form-box -->  
	</div>
	<div class="col-sm-1">&nbsp;</div>
</div>
<!-- bootstrap stuff goes here --->
