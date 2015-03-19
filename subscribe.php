<?php include("system-header.php") ?>

<!--  Start of content -->
<h2>News Letter</h2>

<div class="pad">
<?php
	$email = $_POST['email'];
	
	echo "<P class='actionMessage'>Thank you for your registration.</P>";
	echo "<H4>A confirmation mail has been sent to you.</H4>";
	
	$qry = "INSERT INTO subscribers (email) VALUES ('$email')";
	$result = mysql_query($qry);

	mail(
			"subscribe@prodigyworks.co.uk",
			"Newsletter subscription",
			"Subscription email from : " . $email,
			"from: Subscription Service <subscribe@prodigyworks.co.uk>"
		);

	mail(
			$email,
			"Newsletter subscription",
			"Thank you for your subscription.\nYou will receive our monthly news letter.\n\nSubscription Services\nProdigyWorks!\nWeb Development Studio",
			"from: Subscription Service <subscribe@prodigyworks.co.uk>"
		);
	
?>
</div>
<!--  End of content -->

<?php include("system-footer.php") ?>
