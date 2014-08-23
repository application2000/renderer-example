<?php /* @type  \League\Plates\Template  $this */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css">
	    <title>Joomla! Framework Rendering Example</title>
	</head>
    <body>
        <h1>Framework Rendering Interface Sample</h1>
        <main>
            <?php echo $this->child(); ?>
        </main>
    </body>
</html>
