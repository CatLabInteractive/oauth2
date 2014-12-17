<form method="post" action="<?php echo $action; ?>">
	<label>Do You Authorize <?php echo $clientdata['client_id']; ?>?</label><br />
	<input type="submit" name="authorized" value="yes">
	<input type="submit" name="authorized" value="no">
</form>