<!DOCTYPE html> 
<html lang="en"> 
<head>
	<meta charset="utf-8"> 
	<title>{{ template:title }}</title> 
	{{ theme:css file="themes/docs.css" }}
</head>

<body>

<div id="wrapper" class="container">
	
	<div id="header">
		<h1>{{ module:name }} User Guide Version {{ module:version }}</h1>
	</div> <!-- #header -->

	<div id="toc">
		<ul>
			{{ docs:nav }}
		</ul>
	</div>
	
	<div id="content">
		{{ template:body }}
	</div> <!-- #content -->
	
	<div id="footer">
		<p>{{ docs:prev_topic }} | <a href="#TOC">Top of Page</a> | TOC Title | {{ docs:next_topic }}</p>
		<p><a href="/">{{ module:name }} User Guide</a>, Copyright &copy; {{ helper:date format="Y" }} <a href="{{ module:author_url }}" target="_blank">{{ module:author }}</a></p>
		<p>Documented with <a href="/" target="_blank" title="What is Docs?">Docs</a></p>
	</div> <!-- #footer -->

</div> <!-- #wrapper -->

</body>
</html>