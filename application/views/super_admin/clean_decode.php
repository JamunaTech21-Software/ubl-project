<?php
function clean_decode($str) {
	return html_entity_decode(htmlspecialchars_decode($str));
}
?>