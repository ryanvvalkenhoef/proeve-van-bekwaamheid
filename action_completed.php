<?php
	require_once 'includes/autoloader.php';
	// Plaats hier de code die zorgt voor een verbinding met de database
	$crudObj = new CRUD();

	// Plaats hier de code die checkt of het sign-up formulier verzonden werd (submit). Nieuwe gebruiker aanmaken dus!
	if (isset($_POST['submit'])) echo $crudObj->create();
	
	// Plaats hier de code die checkt of er een DELETE moet plaatsvinden (verwijdering van gebruiker in de database)
	if (isset($_GET['del'])) echo $crudObj->delete();

	// Plaats hier de code die checkt of het update formulier verzonden werd (submit). Bestaande gebruiker updaten dus!
	if (isset($_POST['btnupdate']) && isset($_GET['id'])) echo $crudObj->update();
?>