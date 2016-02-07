<!-- AJAX AUTOSUGGEST SCRIPT -->
<script type="text/javascript" src="scripts/searchautosuggest/lib/ajax_framework.js"></script>

<div id="search-wrap">
    <div id="search_container" style="width:auto;">
    	<form action="modify_roster.php" method="GET" autocomplete="off">
    	<input name="hrap_text" id="hrap_text" type="text" onkeyup="javascript:autosuggest('hrap','hrap_for_roster');"/>
        <input name="hrap_id" id="hrap_id" type="hidden" value="" />
        <input type="hidden" name="crew" value="<?php echo $_SESSION['current_view']['crew']->get('id'); ?>" />
        <input type="hidden" name="function" value="add_existing_hrap" />
        <input type="submit" value="Add this HRAP" class="form_button" />
    	<div id="hrap_results" class="results"></div>
	</div>
</div>

