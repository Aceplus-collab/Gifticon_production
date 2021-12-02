
<?php
 echo "<option value='' >COUNTRY CODE</option>"; 
 foreach ($country_code as $value) {

 	if(isset($selected_code) && ($value['country_code']==$selected_code)){
 		echo "<option  value='".$value['country_code']."' selected>".$value['country_code']." ".$value['country']."</option>";
 	}
 	else{

	echo "<option  value='".$value['country_code']."'>".$value['country_code']." ".$value['country']."</option>";
 	}
}

?>