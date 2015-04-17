<?php
/**
 * Plugin Name: Star Wars Edge of the Empire Character Sheets
 * Plugin URI:
 * Description: Create and maintain your SW: Edge of the empire characters. Mobile Friendly.
 * Author: David Ellenburg
 * Version: 1.1.1
 * Author URI: http://www.ellenburgweb.com
 * License: GPL2
 **/


//Create menu pages
function sweecs_admin_actions() {
	add_menu_page( 'Star Wars Edge of the Empire Character Sheets', 'Character Sheets', 'manage_options', 'SWEECS', 'sweecs_information');
	//add_submenu_page( 'SWEECS', 'Star Wars Edge of the Empire Character Sheets', 'Setup', 'manage_options', 'SWEECSS', 'sweecs_admin');
}
function sweecs_admin() {
	//Admin Stuff
}
function sweecs_information() {
	//Gen Info
	?>
	<h1>Welcome to Star Wars Edge of the Empire Character Sheets</h1>
	<div>
		<p>
			You are currently running version 1.0. In this version you will have the following:
		</p>
		<ul>
			<li>Registered users can create one character.</li>
			<li>Registered users can view their character.</li>
			<li>Registered users can edit their character.</li>
			<li>Registered users can see what dice they are allowed to roll for each skill.</li>
		</ul>

		<p>
			This plugin requires the user to be logged in to see the any of the pages it provides. No need for you to create hidden pages.
		</p>
		<p>
			This plugin uses a simple shortcode. [sweecs page='']<br />
			Below are the pages that you will want to create and then add the shortcode. Only the shortcode is required, you can name the pages how ever you like.
		</p>

		<ul>
			<li>Page: Create new Character, Shortcode: [sweecs page='create']</li>
			<li>Page: Edit Character, Shortcode: [sweecs page='edit']</li>
			<li>Page: View Character, Shortcode: [sweecs page='view']</li>
			<li>Page: Your Dice, Shortcode: [sweecs page='dice']</li>
		</ul>

		<h2>Future Plans</h2>
		<ul>
			<li>Multiple Characters per user</li>
			<li>Talents</li>
			<li>Player Groups so a GM can access his players Characters</li>
			<li>Add expansions</li>
			<li>More as I find things to add</li>
		</ul>

		<p>
			If you like what I am doing with this plugin and have ideas to add to the future list, send me a message at <a href="mailto:ellenburgweb@gmail.com">ellenburgweb@gmail.com</a>
		</p>

	</div>
	<?php
}
// this actually adds the admin panel to the settings page
add_action('admin_menu', 'sweecs_admin_actions');

function sweecs_page($parms){
	if(is_user_logged_in()){
		//Start out by getting the current users info
		$user_info= wp_get_current_user();
		$user_id= $user_info->ID;
		//Set plugin paths
		$dir= plugin_dir_path( __FILE__ );
		$base_url= get_site_url();
		$plugName= 'star-wars-edge-of-the-empire-character-sheets';
		$incPath= "$base_url/wp-content/plugins/$plugName";
		//set tables for pulling data
		global $wpdb;
		$character_table_name=          $wpdb->prefix . 'sweecs_characters';
		$character_talents_table_name=  $wpdb->prefix . 'sweecs_characters_talents';
		$careers_table_name=            $wpdb->prefix . 'sweecs_careers';
		$skills_table_name=             $wpdb->prefix . 'sweecs_skills';
		$talents_table_name=            $wpdb->prefix . 'sweecs_talents';
		?>
		<script src="<?php echo $incPath; ?>/js/prototype.forms.js" type="text/javascript"></script>
		<script src="<?php echo $incPath; ?>/js/jotform.forms.js?3.2.6370" type="text/javascript"></script>
		<script type="text/javascript">
			JotForm.init();
			function spec(career){
				var careers= ['Bounty_Hunter', 'Colonist', 'Explorer', 'Hired_Gun', 'Smuggler', 'Technician'];
				for(var i = 0; i < careers.length; i++){
					var item= careers[i];
					document.getElementById(item).style.display='none';
					var item2= item + '_Spec';
					var field= document.getElementById(item2);
					field.setAttribute("name","nope");
				}
				document.getElementById(career).style.display='block';
				var career2= career + '_Spec';
				var field= document.getElementById(career2);
				field.setAttribute("name","specialization");
			}
			function setDrops(){
				var specID= document.getElementById('career').value;
				document.getElementById(specID).style.display='block';
			}
			function show(skill){
				//document.getElementById(skill).style.display= 'block';

				var skills= ['Astrogation','Athletics','Brawl','Charm','Coercion','Computers','Cool','Coordination','Deception','Discipline','Gunnery','Knowledge','Knowledge_Core_Worlds','Knowledge_Education','Knowledge_Lore','Knowledge_Outer_Rim','Knowledge_Underworld','Knowledge_Xenology','Knowledge_Warfare','Leadership','Mechanics','Medicine','Melee','Negotiation','Perception','Piloting_Planetary','Piloting_Space','Ranged_Heavy','Ranged_Light','Resilience','Skulduggery','Stealth','Streetwise','Survival','Vigilance'];
				for(var i = 0; i < skills.length; i++){
					var item= skills[i];
					document.getElementById(item).style.display='none';
				}
				document.getElementById(skill).style.display='block';
			}

		</script>
	<link href="<?php echo $incPath; ?>/css/css.css?3.2.6370" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="<?php echo $incPath; ?>/css/nova.css?3.2.6370" />
	<link type="text/css" media="print" rel="stylesheet" href="<?php echo $incPath; ?>/css/printForm.css?3.2.6370" />
	<style type="text/css">
		.form-label-left{
			width:150px !important;
		}
		.form-line{
			padding-top:12px;
			padding-bottom:12px;
		}
		.form-label-right{
			width:150px !important;
		}
		.form-all{
			width:650px;
			color:#555 !important;
			font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, sans-serif;
			font-size:14px;
		}
	</style>
		<?php
		//Call the page being asked for
		if(isset($parms['page'])){
			$page= $parms['page'];
			include_once("$dir/pages/$page.php");
		}
	}else{
		echo "You must be logged in to view this page.";
	}

}
//Shortcode [sweecs page='']
add_shortcode( 'sweecs', 'sweecs_page');


//data base insert info
global $sweecs_db_version;
$sweecs_db_version = '1.5';

function sweecs_install() {
	global $wpdb;
	global $sweecs_db_version;
	$character_table_name=          $wpdb->prefix . 'sweecs_characters';
	$character_talents_table_name=  $wpdb->prefix . 'sweecs_characters_talents';
	$careers_table_name=            $wpdb->prefix . 'sweecs_careers';
	$skills_table_name=             $wpdb->prefix . 'sweecs_skills';
	$talents_table_name=            $wpdb->prefix . 'sweecs_talents';

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $character_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		userID text NOT NULL,
		player text NOT NULL,
		character_name text NOT NULL,
		race text NOT NULL,
		career text NOT NULL,
		specialization text NOT NULL,
		soak text NOT NULL,
		wounds text NOT NULL,
		strain text NOT NULL,
		defence text NOT NULL,
		brawn text NOT NULL,
		agility text NOT NULL,
		intellect text NOT NULL,
		cunning text NOT NULL,
		willpower text NOT NULL,
		presence text NOT NULL,
		Astrogation text NOT NULL,
		Athletics text NOT NULL,
		Brawl text NOT NULL,
		Charm text NOT NULL,
		Coercion text NOT NULL,
		Computers text NOT NULL,
		Cool text NOT NULL,
		Coordination text NOT NULL,
		Deception text NOT NULL,
		Discipline text NOT NULL,
		Gunnery text NOT NULL,
		Knowledge text NOT NULL,
		Knowledge_Core_Worlds text NOT NULL,
		Knowledge_Education text NOT NULL,
		Knowledge_Lore text NOT NULL,
		Knowledge_Outer_Rim text NOT NULL,
		Knowledge_Underworld text NOT NULL,
		Knowledge_Xenology text NOT NULL,
		Knowledge_Warfare text NOT NULL,
		Leadership text NOT NULL,
		Mechanics text NOT NULL,
		Medicine text NOT NULL,
		Melee text NOT NULL,
		Negotiation text NOT NULL,
		Perception text NOT NULL,
		Piloting_Planetary text NOT NULL,
		Piloting_Space text NOT NULL,
		Ranged_Heavy text NOT NULL,
		Ranged_Light text NOT NULL,
		Resilience text NOT NULL,
		Skulduggery text NOT NULL,
		Stealth text NOT NULL,
		Streetwise text NOT NULL,
		Survival text NOT NULL,
		Vigilance text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	//
	$sql = "CREATE TABLE $character_talents_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		talent_id text NOT NULL,
		char_id text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql );
	//Version 1.1.1 added Careers
	$sql = "CREATE TABLE $careers_table_name (
		id mediumint(9) NOT NULL,
		career text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql );
	//insert data
	//$careers= array('Bounty_Hunter', 'Colonist', 'Explorer', 'Hired_Gun', 'Smuggler', 'Technician');
	$careers= array(
		array('Bounty_Hunter',1),
		array('Colonist',2),
		array('Explorer',3),
		array('Hired_Gun',4),
		array('Smuggler',5),
		array('Technician',6)
	);
	foreach($careers as $career){
		$wpdb->insert(
			$careers_table_name,
			array(
				'career' => $career[0],
				'id'     => $career[1]
			)
		);
	}
	//Version 1.1.2 added skills
	$sql = "CREATE TABLE $skills_table_name (
		id mediumint(9) NOT NULL,
		skill text NOT NULL,
		ability text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql );
	//insert data
	$skills= array(
		array('Astrogation','intellect',1),
		array('Athletics','brawn',2),
		array('Brawl','brawn',3),
		array('Charm','presence',4),
		array('Coercion','willpower',5),
		array('Computers','intellect',6),
		array('Cool','presence',7),
		array('Coordination','agility',8),
		array('Deception','cunning',9),
		array('Discipline','willpower',10),
		array('Gunnery','agility',11),
		array('Knowledge','intellect',12),
		array('Knowledge_Core_Worlds','intellect',13),
		array('Knowledge_Education','intellect',14),
		array('Knowledge_Lore','intellect',15),
		array('Knowledge_Outer_Rim','intellect',16),
		array('Knowledge_Underworld','intellect',17),
		array('Knowledge_Xenology','intellect',18),
		array('Knowledge_Warfare','intellect',19),
		array('Leadership','presence',20),
		array('Mechanics','intellect',21),
		array('Medicine','intellect',22),
		array('Melee','brawn',23),
		array('Negotiation','presence',24),
		array('Perception','cunning',25),
		array('Piloting_Planetary','agility',26),
		array('Piloting_Space','agility',27),
		array('Ranged_Heavy','agility',28),
		array('Ranged_Light','agility',29),
		array('Resilience','brawn',30),
		array('Skulduggery','cunning',31),
		array('Stealth','agility',32),
		array('Streetwise','cunning',33),
		array('Survival','cunning',34),
		array('Vigilance','willpower',35)
		);
	foreach($skills as $skill){
		$wpdb->insert(
			$skills_table_name,
			array(
				'skill'     => $skill[0],
				'ability'   => $skill[1],
				'id'        => $skill[2]
			)
		);
	}
	//Version 2.0 Add talents
	$sql = "CREATE TABLE $talents_table_name (
		talent_id mediumint(9) NOT NULL,
		career text NOT NULL,
		specialization text NOT NULL,
		talent text NOT NULL,
		description text NOT NULL,
		style text NOT NULL,
		cost text NOT NULL,
		requires text,
		UNIQUE KEY id (id)
	) $charset_collate;";
	dbDelta( $sql );
	//Insert Data
	$talents= array(
		array('Bounty_Hunter','Assassin','Grit','Gain + 1 strain threshold.','Passive',5,1,''),
		array('Bounty_Hunter','Assassin','Lethal_Blows','Add + 10 per rank of Lethal blows to any Critical Injury inflicted on opponents.','Passive',5,2,''),
		array('Bounty_Hunter','Assassin','Stalker','Add one blue dice per rank of Stalker to all Stealth and Coordination checks.','Passive',5,3,''),
		array('Bounty_Hunter','Assassin','Dodge','When targeted by combat check, may perform a Dodge incidental to suffer a number of strain no greater than ranks of Dodge, then upgrade the difficulty of the check by that number.','Active',5,4,''),
		array('Bounty_Hunter','Assassin','Precise_Aim','Once per round, may perform Precise AIM maneuver. Suffer a number of strain no greater than ranks in Precise Aim, then reduce target’s melee and ranged defence by that number.','Active',10,5,'1'),
		array('Bounty_Hunter','Assassin','Jump_Up','Once per round, may stand from seated or prone as an incidental.','Active',10,6,'2,5'),
		array('Bounty_Hunter','Assassin','Quick_Strike','Add one blue dice per rank of Quick Strike to combat checks against targets that have not acted yet this encounter.','Passive',10,7,'3,6'),
		array('Bounty_Hunter','Assassin','Quick_Draw','Once per round, draw or holster a weapon or accessible item as an incidental.','Active',10,8,'7'),
		array('Bounty_Hunter','Assassin','Targeted_Blow','After making a successful attack, may spend 1 Destiny Point to add damage equal to Agility to one hit.','Active',15,9,'5'),
		array('Bounty_Hunter','Assassin','Stalker','Add one blue dice per rank of Stalker to all Stealth and Coordination checks.','Passive',15,10,'6'),
		array('Bounty_Hunter','Assassin','Lethal_Blows','Add + 10 per rank of Lethal blows to any Critical Injury inflicted on opponents.','Passive',15,11,'10,7'),
		array('Bounty_Hunter','Assassin','Anatomy_Lessons','After making a successful attack, may spend 1 Destiny Point to add damage equal to Intellect to one hit.','Active',15,12,'8'),
		array('Bounty_Hunter','Assassin','Stalker','Add one blue dice per rank of Stalker to all Stealth and Coordination checks.','Passive',20,13,'9'),
		array('Bounty_Hunter','Assassin','Sniper_Shot',"Before making a non-thrown ranged attack, may perform a Sniper Shot maneuver to increase the weapon's range by 1 range band per rank in Sniper Shot. Upgrade the difficulty of the attack by 1 per range band increase.",'Active',20,14,'13,10'),
		array('Bounty_Hunter','Assassin','Dodge','When targeted by combat check, may perform a Dodge incidental to suffer a number of strain no greater than ranks of Dodge, then upgrade the difficulty of the check by that number.','Active',20,15,'11'),
		array('Bounty_Hunter','Assassin','Lethal_Blows','Add + 10 per rank of Lethal blows to any Critical Injury inflicted on opponents.','Passive',20,16,'12'),
		array('Bounty_Hunter','Assassin','Precise_Aim',"Once per round, may perform Precise AIM maneuver. Suffer a number of strain no greater than ranks in Precise Aim, then reduce target's melee and ranged defence by that number.",'Active',25,17,'13'),
		array('Bounty_Hunter','Assassin','Deadly_Accuracy','When acquired, choose 1 combat skill. Add damage equal to ranks in that skill to one hit of a successful attack made using that skill.','Passive',25,18,'14'),
		array('Bounty_Hunter','Assassin','Dedication','Gain +1 to a single characteristic. This cannot bring a characteristic above 6.','Passive',25,19,'15'),
		array('Bounty_Hunter','Assassin','Master_of_Shadows','Once per round, suffer 2 strain to decrease difficulty of next Stealth or Skulduggery check by one.','Active',25,20,'16')
	);
	foreach($talents as $talent){
		$wpdb->insert(
			$talents_table_name,
			array(
				'career'            => $talent[0],
				'specialization'    => $talent[1],
				'talent'            => $talent[2],
				'description'       => $talent[3],
				'style'             => $talent[4],
				'cost'              => $talent[5],
				'talent_id'         => $talent[6],
				'requires'          => $talent[7]
			)
		);
	}

	update_option( 'sweecs_db_version', $sweecs_db_version );
}
//calling the db install only on activation
register_activation_hook( __FILE__, 'sweecs_install' );
//Update DB Tables
function sweecs_update_db_check() {
	global $sweecs_db_version;
	if ( get_option( 'sweecs_db_version' ) != $sweecs_db_version ) {
		sweecs_install();
	}
}
add_action( 'plugins_loaded', 'sweecs_update_db_check' );