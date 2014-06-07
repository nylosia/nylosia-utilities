<?php
	header('Content-type: text/json');

	//GET ?[userid=...&]postid=...&vote=...	-> aggiorno voto
	//GET ?[userid=...&]postid=...			-> ritorna voto utente, numero totale di voti, media voti

	$ny_r_meta_type = 'nylosiarating_term';
	$cookie_name = 'nylosia_rating_uid';

	//se non è specificato leggo l'utente dai cookie
	if (isset($_GET['userid']) && !isempty($_GET['userid'])) {
		$user_id = $_GET['userid'];
	} else {
		//se non esiste lo creo, se no aggiorno la scadenza
		if (!isset($_COOKIE[$cookie_name])) {
		    setcookie($cookie_name, get_unique_id(), time() + 5184000); //60gg
		} else {
			setcookie($cookie_name, $_COOKIE[$cookie_name], time() + 5184000); //60gg
		}

		$user_id = $_COOKIE[$cookie_name];
	}

	if (isset($_GET['postid'])) {
		$post_id = $_GET['postid'];

		if (isset($_GET['vote'])) {
			$vote = $_GET['vote'];
			//se il voto è positivo aggiorno, altrimenti cancello
			if ($vote > 0) {
				$resp = ( update_metadata( $ny_r_meta_type, $post_id, $user_id, $vote ) ? "true" : "false" );
			} else {
				$resp = ( delete_metadata( $ny_r_meta_type, $post_id, $user_id ) ? "true" : "false" );
			}
		}
		
		//ritorna sempre il riepilogo della situazione
		$vote = get_metadata( $ny_r_meta_type, $post_id, $user_id );
		$votes = get_metadata( $ny_r_meta_type, $post_id );
		$totratings = count($votes);
		$avg = array_sum($votes) / count($votes);

		echo "{ \"postid\": \"{$post_id}\", \"userid\": \"{$user_id}\", \"vote\": \"{$vote}\", \"totratings\": {$totratings}, \"avg\": {$avg} }";
	} else {
		echo "{ \"postid\": undefined, \"userid\": \"{$user_id}\" }";
	}
	
?>