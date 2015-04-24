<?php
/**
 * Created by PhpStorm.
 * User: dellenburg
 * Date: 3/31/2015
 * Time: 13:47
 * Name: Talent Chooser
 * Desc: This script will allow the players to add talents to their character.
 */

@$character_atts= $wpdb->get_row("SELECT * FROM $character_table_name WHERE userID = $user_id", ARRAY_A);
$character_career= $character_atts['career'];
$character_specialization= $character_atts['specialization'];
$charID= $character_atts['id'];

$talents= $wpdb->get_results("SELECT * FROM $talents_table_name WHERE specialization = '$character_specialization'", ARRAY_A);

if(isset($_REQUEST['addTal'])){
	foreach($_REQUEST as $talentName =>$addedTalents){
		if($addedTalents == 'on'){
			$cut=strpos($talentName,'_');
			$talent_id= str_replace("_"," ",substr("$talentName", 0, $cut));
			$check= $wpdb->get_row("SELECT * FROM $character_talents_table_name WHERE talent_id = $talent_id and char_id = $charID", OBJECT);
			if(isset($check)){
				//don't add
			}else{
				$wpdb->insert(
					$character_talents_table_name,
					array(
						'talent_id' => $talent_id,
						'char_id'   => $charID,
					)
				);
			}
		}
	}
}
?>

<form class="jotform-form" action="" method="post" name="form_50895250335153" id="50895250335153" accept-charset="utf-8">
	<input type="hidden" name="addTal" value="" />
	<div>
		<ul class="form-section page-section">
			<li id="cid_1" class="form-input-wide" data-type="control_head">
				<div class="form-header-group">
					<div class="header-text httal htvam">
						<h2 id="header_1" class="form-header">
							Please choose your talents. Don't forget to hit save
						</h2>
						<h4>
							You are responsible for not adding talents you do not meet the requirements for.
						</h4>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<div>
		<ul>
	<?php
	//echo $talents['talent'];
	$i= 1;
	foreach($talents as $talent){
		$talent_id_check= $talent['talent_id'];
		$check= $wpdb->get_row("SELECT * FROM $character_talents_table_name WHERE talent_id = $talent_id_check and char_id = $charID", OBJECT);
		if(isset($check)){
			$checked= 'checked';
		}else{
			$checked= '';
		}
	?>
				<li class="form-line" data-type="control_textbox" id="id_<? echo $i; ?>" >
					<label class="form-label form-label-left form-label-auto" id="label_<? echo $i; ?>" for="input_<? echo $i; ?>">
						<? echo str_replace("_"," ",$talent['talent']); ?>
					</label>
					<div id="cid_<? echo $i; ?>" class="form-input">
						<input type="checkbox" id="input_<? echo $i; ?>" name="<? echo $talent['talent_id'].'_'.$talent['talent']; ?>" <? echo $checked; ?> />
					</div>
				</li>
	<?php
		$i++;
	}
	?>
		</ul>
	</div>
	<br clear="all"/>
	<!--li class="form-line" data-type="control_button" id="id_18"-->
	<div id="cid_18" class="form-input-wide">
		<div style="margin-left:156px" class="form-buttons-wrapper">
			<button id="input_18" type="submit" class="form-submit-button">
				Save
			</button>
		</div>
	</div>
	<!--/li-->
	<input type="hidden" id="simple_spc" name="simple_spc" value="50895250335153" />
	<script type="text/javascript">
		document.getElementById("si" + "mple" + "_spc").value = "50895250335153-50895250335153";
	</script>
</form>