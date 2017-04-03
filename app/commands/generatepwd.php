<?php
/**
 * CLI : $ php app/commands/generatepwd.php xxx
 * URI : /app/commands/generatepwd.php?pwd=xxx
 *
 * Paste the result on config/conf.php $this->loginKey
 *
 */

if ( isset($_SERVER['argv']) ) {
	// variable command line
	$vars = $_SERVER['argv'];
	$varsPwd = $vars[1];
}

if (isset(htmlspecialchars($_GET['model']))) {
	// variable URI
	$varsPwd = htmlspecialchars($_GET['pwd']);
}

if(empty($varsPwd)) return print 'error';

if (version_compare(phpversion(), '5.5.0', '<')) {

	/* if PHP < 5.5 */
	print md5($varsPwd);
	print '
	';

} else  {

	/* if PHP >= 5.5 */
	$options = [
	'cost' => 12,
	];

	print password_hash($varsPwd, PASSWORD_BCRYPT, $options);
	print '
	';
}
