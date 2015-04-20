<?php
/**
 * Created by PhpStorm.
 * User: dellenburg
 * Date: 3/31/2015
 * Time: 13:47
 * Name: Inventory
 * Desc: This script will control a players inventory.
 */

//set up variables for page
$character_atts= $wpdb->get_row("SELECT * FROM $character_table_name where userID = $user_id", ARRAY_A);
$charID= $character_atts['id'];

//Update inventory
if(isset($_REQUEST['update'])){
	$update_credits= $_REQUEST['Credits'];
	$update_inventory= $_REQUEST['Inventory'];

	$update_char_inv= $wpdb->update(
		$character_inventory_table_name,
		array(
			'Credits'   => $update_credits,
			'Inventory' => $update_inventory
		),
		array( 'char_id' => $charID)
	);
	if($update_char_inv >0){
		echo 'Your inventory has been updated';
	}else{
		$update_char_inv= $wpdb->insert(
			$character_inventory_table_name,
			array(
				'Credits'   => $update_credits,
				'Inventory' => $update_inventory,
				'char_id'   => $charID
			)
		);
	}
}

$character_inventory= $wpdb->get_row("SELECT * FROM $character_inventory_table_name where char_id = $charID", ARRAY_A);
$credits= $character_inventory['Credits'];
$inventory= $character_inventory['Inventory'];

?>
<h1>Inventory for <?echo $character_atts['character_name'];?></h1>

<form action="" method="post">
	<input type="hidden" name="update" />
	Credits
	<br />
	<input type='text' name='Credits' style='width:150px' value='<? echo $credits; ?>' />
	<br />
	Inventory
	<br />
	<input type='text' name='Inventory' value='<? echo $inventory; ?>'/>
	<div id="cid_18" class="form-input-wide">
		<div style="margin-left:156px" class="form-buttons-wrapper">
			<button id="input_18" type="submit" class="form-submit-button">
				Update
			</button>
		</div>
	</div>
</form>