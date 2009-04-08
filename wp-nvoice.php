<?php
/**
 * @package NVoice
 * @author Michell Hoduń <contact@ntechnology.pl>
 * @version 1.0
 */
/*
Plugin Name: NVoice
Description: Listen your posts, using <a href="http://www.ivona.com/online/">IVONA Online</a> <a href="http://www.ivona.com/online/license.php">[license]</a>.
Author: Michell Hoduń
Version: 1.0
Author URI: http://www.ntechnology.pl
Plugin URI: http://www.ntechnology.pl
*/

require_once(dirname(__FILE__) . '/NVoice.class.php');



function nvoice_install() {		
	add_option('nvoice_account', 'my@mail.com');
	add_option('nvoice_password', '');
	add_option('nvoice_voice', '3');
	add_option('nvoice_soap', 'http://www.ivona.com/online/apispwsdl.php');
}

if (isset ($_GET['activate']) && $_GET['activate'] == 'true') {
	add_action('init', 'nvoice_install');
}

if(get_option('nvoice_account') != "" && get_option('nvoice_soap') != "" && get_option('nvoice_voice') != "") {
$NVoice_Ivona = new NVoice_Ivona(get_option('nvoice_account'), get_option('nvoice_password'), get_option('nvoice_soap'), get_option('nvoice_voice'));
}


function add_custom_field($id) {
	delete_post_meta($id, 'nvoice');

	$NVoice_Ivona = new NVoice_Ivona(get_option('nvoice_account'),  get_option('nvoice_password'), get_option('nvoice_soap'), get_option('nvoice_voice'));

	$url = $NVoice_Ivona -> DodajNagranie(get_the_title($id), strip_tags($_POST['content']));

	add_post_meta($id, 'nvoice', $url);

}




function nvoice() {

	nvoice_update();


  $NVoice_Ivona = new NVoice_Ivona(get_option('nvoice_account'), get_option('nvoice_password'), get_option('nvoice_soap'), get_option('nvoice_voice'));;



	echo '<style type="text/css">';
	echo '#autoblogleft { float: left; width:40%; padding-right: 5%;}';
	echo '#autoblogright { float: left; width:40%; }';
	echo '#autoblogcenter { text-align:center; background: #FF3333; }';
	echo '</style>';

	echo '<div class="wrap">';
	echo '<h2>NVoice - '.__('Settings').'</h2>';

	echo '<form action="options-general.php?page=' . $_GET['page'] . '&updated=true" method="post">';


	echo '<table class="form-table">';
	echo '<tr valign="top">';
	echo '<th scope="row"><label for="nvoice_account">'.__('E-mail address').':</label></th>';
	echo '<td><input name="nvoice_account" type="text" id="nvoice_account" value="'.get_option('nvoice_account').'" class="regular-text" /></td>';
	echo '</tr>';


	echo '<tr valign="top">';
	echo '<th scope="row"><label for="nvoice_password">'.__('Password').':</label></th>';
	echo '<td><input name="nvoice_password" type="password" id="nvoice_password" value="'.get_option('nvoice_password').'" class="regular-text" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row"><label for="nvoice_voice">Voice:</label></th>';
	echo '<td>';

	echo '<select name="nvoice_voice" id="nvoice_voice">';

	$voices = $NVoice_Ivona->client->__soapCall('listVoices', array());


	foreach ($voices as $v) {

		echo '<option value="'.$v->voiceId.'" '.((get_option('nvoice_voice') == $v->voiceId)?' selected':'').'>'.$v->voiceName.' ('.$v->langId.')</option>';

	}


	echo '</select>';
	echo '</td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row"><label for="nvoice_soap">SOAP URL:</label></th>';
	echo '<td><input name="nvoice_soap" type="text" id="nvoice_soap" value="'.get_option('nvoice_soap').'" class="regular-text" /></td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="update" value="true" />';

	echo '<p class="submit"><input type="submit" name="Submit" class="button-primary" value="'.__('Save Changes').'" /></p></form>';

	echo '<p>';
	echo '<strong>';
	echo 'NVoice &copy; 2009 by <a href="http://www.ntechnology.pl">nTechnology.pl</a>';
	echo '</strong>';
	echo '</p>';
	
	echo '</div>';
}

function nvoice_add_options_to_admin() {
	add_options_page('NVoice', 'NVoice', 8, __FILE__, 'nvoice');
}

if (function_exists('add_action')) {
	add_action('admin_menu', 'nvoice_add_options_to_admin');

	add_action('edit_post', 'add_custom_field');
	add_action('publish_post', 'add_custom_field');
	add_action('save_post', 'add_custom_field');
	add_action('edit_page_form', 'add_custom_field');
}

function nvoice_update() {

	if (isset ($_POST['update'])) {	
		update_option('nvoice_account', $_POST['nvoice_account']);
		update_option('nvoice_password', $_POST['nvoice_password']);
		update_option('nvoice_soap', $_POST['nvoice_soap']);
		update_option('nvoice_voice', $_POST['nvoice_voice']);
	}
}
?>