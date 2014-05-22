<html>
<head>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

{foreach $css_files as $link}
<link  name={$link@key} rel="stylesheet" type="text/css" href="{$resource_path}/{$link}"/>
{/foreach} 

<script type="text/javascript">
var RESOURCE_PATH = '{$resource_path}';
</script>
{foreach $script_files as $src}
<script name={$src@key} src="{$resource_path}/{$src}" type="text/javascript"></script>
{/foreach}

</head>
<body>

{include file="$body_file" title="body"}

</body>

</html>
