<?php
/**
 * Plugin Name: Star Wars Edge of the Empire Character Sheets
 * Plugin URI:
 * Description: Create and maintain your SW: Edge of the empire characters. Mobile Friendly.
 * Author: David Ellenburg
 * Version: 1.5.1
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
		$character_table_name = $wpdb->prefix . 'sweecs_characters';
		$careers_table_name = $wpdb->prefix . 'sweecs_careers';
		$skills_table_name = $wpdb->prefix . 'sweecs_skills';
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
$sweecs_db_version = '1.1';
function sweecs_install() {
	global $wpdb;
	global $sweecs_db_version;
	$character_table_name=              $wpdb->prefix . 'sweecs_characters';
	$character_inventory_table_name=    $wpdb->prefix . 'sweecs_characters_inventory';
	$careers_table_name=                $wpdb->prefix . 'sweecs_careers';
	$skills_table_name=                 $wpdb->prefix . 'sweecs_skills';

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
	//Character inventory and Credits
	$sql = "CREATE TABLE $character_inventory_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		char_id text NOT NULL,
		Credits int,
		Inventory text,
		UNIQUE KEY id (id)
	)$charset_collate;";
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
				'id'        =>  $skill[2]
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