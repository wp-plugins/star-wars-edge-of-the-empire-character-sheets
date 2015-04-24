<?php
/**
 * Created by PhpStorm.
 * User: dellenburg
 * Date: 3/31/2015
 * Time: 13:47
 * Name: View Talents
 * Desc: This script will allow the players to view talents on their character.
 */

@$character_atts= $wpdb->get_row("SELECT * FROM $character_table_name WHERE userID = $user_id", ARRAY_A);
$character_career= $character_atts['career'];
$character_specialization= $character_atts['specialization'];
$charID= $character_atts['id'];

$characters_talents= $wpdb->get_results("SELECT * FROM $character_talents_table_name WHERE char_id = $charID", ARRAY_A);

//$talents= $wpdb->get_results("SELECT * FROM $talents_table_name WHERE specialization = '$character_specialization'", ARRAY_A);

echo "<h2> Your Talents</h2>";
echo "<ul>";
$i= 1;
foreach($characters_talents as $talents){
	$talent_id= $talents['talent_id'];
	$talent_name= $wpdb->get_results("SELECT * FROM $talents_table_name WHERE talent_id = $talent_id", ARRAY_A);
	echo "<li class='form-line' data-type='control_textbox' id='id_$i' >";
	echo "<p>Talent: ".str_replace("_"," ",$talent_name[0]['talent'])."</p>";
	echo "<p>Specialization: ".str_replace("_"," ",$talent_name[0]['specialization'])."</p>";
	echo "</li>";
	$i++;
}
echo "</ul>";