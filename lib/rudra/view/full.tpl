<html>
<head>
<meta name="viewport"
	content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

{foreach $CSS_FILES as $link}
<link  name={$link@key} rel="stylesheet" type="text/css" href="{$CONTEXT_PATH}{$RESOURCE_PATH}/{$link}"/>
{/foreach} 

<script type="text/javascript">
var RESOURCE_PATH = '{$RESOURCE_PATH}';
var CONTEXT_PATH = '{$CONTEXT_PATH}';
</script>
{foreach $SCRIPT_FILES as $src}
<script name={$src@key} src="{$CONTEXT_PATH}{$RESOURCE_PATH}/{$src}" type="text/javascript"></script>
{/foreach}

</head>
<body>

{include file="$BODY_FILES" title="body"}

</body>

</html>
