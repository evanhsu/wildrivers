<?php 
include_once("./classes/mydb_class.php");

	$searchq		=	strip_tags($_GET['q']);
	$getRecord_sql	=	'SELECT distinct * FROM hraps WHERE lastname LIKE "'.$searchq.'%"';
	$getRecord		=	mydb::cxn()->query($getRecord_sql);
	if(strlen($searchq)>0){
	// ---------------------------------------------------------------- // 
	// AJAX Response													// 
	// ---------------------------------------------------------------- // 
	
	// Change php echo $row['name']; and $row['department']; with the	//
	// name of table attributes you want to return. For Example, if you //
	// want to return only the name, delete the following code			//
	// "<br /><?php echo $row['department'];></li>"//
	// You can modify the content of ID element how you prefer			//
	echo '<ul>';
	while ($row = $getRecord->fetch_assoc()) {?>
		<li><a href="#"><?php echo $row['lastname']; ?> <small><?php echo $row['lastname']; ?></small></a></li>
	<?php } 
	echo '</ul>';
	?>
<?php } ?>