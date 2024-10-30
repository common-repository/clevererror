<?php
/**
 * @package Clever_Error
 * @version 0.1
 */
/*
	Plugin Name: CleverError - Make your error pages more clever
	Plugin URI: http://clevererror.com
	Description: CleverError intelligently replace your old 404 'Page Not Found'-page with a supercharged version with an internal site search that increase visitor engagement and ensure that your visitors stay on your site. 
	Author: TomPod
	Version: 0.20
	Author URI: http://tompod.com
*/


function Clever_Error_process() {
	//caught error, redirect - check for 404
	if (is_404()) {
		$dbCleverErrorOptions = array();
		$dbCleverErrorOptions = unserialize(get_option('clevererror_options'));

		//check for specific language 
		if ($dbCleverErrorOptions['language']) {
			$uriLanguage = '&l=' . $dbCleverErrorOptions['language'];
		}

		//pass along to catch.clevererror.com
		header('Location: http://catch.clevererror.com/?host=' . $_SERVER["HTTP_HOST"] . '&query=' . $_SERVER["REQUEST_URI"] . $uriLanguage);
	}
}

add_action('template_redirect', 'Clever_Error_process');

//
// admin
//

// options page
function Clever_Error_options() {
	// if submitted, process results
	if ($_POST["clevererror_submit"] ) {
		$dbCleverErrorOptions = array();
		$dbCleverErrorOptions['language'] = stripslashes($_POST["clevererror_language"]);
		update_option('clevererror_options', serialize($dbCleverErrorOptions));
	} else {
		//get our options
		$dbCleverErrorOptions = array();
		$dbCleverErrorOptions = unserialize(get_option('clevererror_options'));
		
		//override values (and set this if none)
		if (!$dbCleverErrorOptions['language']) {
			$dbCleverErrorOptions['language'] = 0;
		}
	}

	// options form
	echo '
		<script type="text/javascript">
			function cleverErrorPreview() {
				window.open("http://catch.clevererror.com?h=' . $_SERVER['SERVER_NAME'] . '&q=' . urlencode(get_bloginfo('name')) . '&l=' . $dbCleverErrorOptions['language'] . '");
			}
		</script>
	';
	echo '<div class="wrap"><form method="post">';
	echo '<div id="icon-plugins" class="icon32"><br></div><h2>CleverError - Make your error pages more clever <a href="#" onclick="cleverErrorPreview();" class="add-new-h2">Show Current Errorpage</a></h2>';
	echo '<br class="clear" />
				<h3 class="title">Customization</h3>				
				<table class="form-table">';
					// Language
					$cleverErrorLanguageOptions = array(
						'0' => 'Let the browser decide',
						'en_US' => 'English',
						'sv_SE' => 'Swedish',
						'de_DE' => 'German',
						'nl_NL' => 'Dutch'
					);

					echo '<tr valign="top"><th scope="row">Default Errorpage Language:</th>';
					echo '<td>
									<select name="clevererror_language">
										';
										foreach ($cleverErrorLanguageOptions as $cleverErrorLanguageKey => $cleverErrorLanguage) {
											if ($cleverErrorLanguageKey === $dbCleverErrorOptions['language']) {
												$cleverErrorSelected = 'SELECTED';
											} else {
												$cleverErrorSelected = '';
											}
											echo '<option value="' . $cleverErrorLanguageKey . '" ' . $cleverErrorSelected . '>' . $cleverErrorLanguage . '</option>';
										}
					echo '
									</select>
								<p class=\'help\'>Unless your website is in a specific language we recommend that you let the users browser decide the language on the errorpage.</div></p></td></tr>';
					
					echo '
					</table>
							<p class="submit">
								<input type="hidden" name="clevererror_submit" value="true"></input>
								<input type="submit" class="button button-primary" value="Update Options &raquo;"></input>
							</p>
					</form>
					';
				
				
				
				echo '
				<table cellspacing="0">
				<tr>
				<td style="width:50%; padding-right:30px;" valign="top">
				<div>
				<h3>What is CleverError, and how does it work?</h3>
					<p>
						CleverError intelligently replace your old 404 \'Page Not Found\'-page with a supercharged version with an internal site search that increase visitor engagement and ensure that your visitors stay on your site.
						<br /><br />
						So rather than losing a visitor when they mistype a URL, follow an old link or end up where they shouldn\'t you provide them with an easy and elegant way to find their way back to your site and content. Read more about us at <a href="http://clevererror.com">clevererror.com</a>
					</p>
				</div>
				</td>
				<td style="width:50%; padding-right:30px;" valign="top">
				
				<div>
				<h3>Sponsor CleverError.com</h3>
					If you like and wish to support our service you can either donate though paypay using the button below:
					<br /><br />
					<center> 
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="WLFFEZMSJ8WCS">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
						<img alt="" border="0" src="https://www.paypalobjects.com/sv_SE/i/scr/pixel.gif" width="1" height="1">
						</form>
					</center> 

				</div>
				</td>
				</tr>
				</table>
			';

	echo "</div>";
	echo '</form></div>';
}

function addclevererrorsubmenu() {
    add_submenu_page('plugins.php', 'CleverError', 'CleverError', 10, __FILE__, 'Clever_Error_options'); 
}
add_action('admin_menu', 'addclevererrorsubmenu');
?>
