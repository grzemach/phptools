<?php
$startTime = microtime(true);
error_reporting(E_ALL);
require_once 'include/config.php';
require_once 'include/generate.php';
$generateClass = new GeneratePasswords($defaults);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Simple password generator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
</head>
<body>
<div class="mainContainer">
	<h1>Simple password generator</h1>
	<div class="outputContainer">
		<div class="settings">
			<h2>Settings</h2>
			<form method="post" action="">
				<div class="input">
					<label for="passwordLength">Passwords length</label>
					<input type="number" class="numberField" id="passwordLength" name="passwordLength" value="<?php echo $generateClass->getValue('passwordLength') ?>">
				</div>
				<div class="input">
					<label for="passwordNumber">Passwords amount</label>
					<input type="number" class="numberField" id="passwordNumber" name="passwordNumber" value="<?php echo $generateClass->getValue('passwordNumber') ?>">
				</div>

				<div class="input">
					<label for="lowerChars">Lowercase characters (a-z)</label>
					<input type="checkbox" class="checkboxField" id="lowerChars" name="lowerChars" <?php echo ($defaults['lowerChars']?'checked':'') ?> value="1">
				</div>

				<div class="input">
					<label for="capitalChars">Uppercase characters (A-Z)</label>
					<input type="checkbox" class="checkboxField" id="capitalChars" name="capitalChars" <?php echo ($defaults['capitalChars']?'checked':'') ?> value="1">
				</div>

				<div class="input">
					<label for="digitsChars">Digits (0-9)</label>
					<input type="checkbox" class="checkboxField" id="digitsChars" name="digitsChars" <?php echo ($defaults['digitsChars']?'checked':'') ?> value="1">
				</div>

				<div class="input">
					<label for="specialChars">Spacial characters (@#$|]{)</label>
					<input type="checkbox" class="checkboxField" id="specialChars" name="specialChars" <?php echo ($defaults['specialChars']?'checked':'') ?> value="1">
				</div>

				<div class="input">
					<label for="punctuationChars">Punctuation characters (,.;?)</label>
					<input type="checkbox" class="checkboxField" id="punctuationChars" name="punctuationChars" <?php echo ($defaults['punctuationChars']?'checked':'') ?> value="1">
				</div>
				<div class="input caption">
					<!--                            <label></label>-->
					<input type="submit" name="generate" value="Generate new" id="generateButton" class="btn btn-primary submit-button"/>
				</div>
			</form>
		</div>
		<div class="output">
			<h2>Passwords</h2>
			<?php
			$generateClass->generate();
			echo $generateClass->getErrorMessage();
			echo $generateClass->getOutput();
			?>
		</div>
	</div>
	<div id="footer">
		<div class="pull-left">&copy; 2016 <a href="http://grzemachinternetservices.com">Grzemach Internet Services</a></div>
		<div class="pull-right"><a href="http://tools.grzemachinternetservices.com">Other tools</a></div>
	</div>
</div>
<?php
$endTime = microtime(true);

//    echo 'generate time '.sprintf('%.16f',$endTime-$startTime).'';
?>
</body>
</html>