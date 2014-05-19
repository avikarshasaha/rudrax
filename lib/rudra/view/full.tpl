<html>
<head>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	  
	{foreach $css_files as $link}
		<link rel="stylesheet" type="text/css" href="{$resource_path}/{$link}">
	{/foreach}
	
	{foreach $script_files as $src}
	<script src="{$resource_path}/{$src}" type="text/javascript"></script>
	{/foreach}
	
	
</head>
<ul>
<li>startes</li> 

	<li>ends</li> 
</ul>
<body>{include file="$body_file" title="body"}
</body>
</html>
