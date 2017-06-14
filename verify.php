<?php
require_once('../../../wp-load.php');

$wpdb = $GLOBALS['wpdb'];

// Use the email verification here.
$hash = htmlspecialchars($_GET['hash']);

if(!isset($hash) || empty($hash)) { die; }

// Let's verify?
$query = $wpdb->get_row('SELECT * FROM `teams` WHERE `email_ver`=' . $hash . ' LIMIT 1', ARRAY_A);
if(!$query) {
    get_header();
    ?>
        <h1>Neispravan kod!</h1>
    <?php
    get_footer();
    die;
}

if($query['email_ver'] == $hash && $query['verified'] == 0) {
    if($wpdb->update('teams', array('verified' => 1), array('email_ver' => $hash), array('%d'))) { ?>
        <h1>Potvrđeno uspješno!</h1>
    <?php }
}else {
    get_header(); ?>
    <h1>Već potrvđeno!</h1>
    <?php
    get_footer();
}

?>