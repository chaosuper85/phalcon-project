<!DOCTYPE html>
{%html framework="common/static/script/mod.js"%}
<head>
	{%block name="block_head_seo"%}
    	<title>箱典典</title>
    	<meta name="description" content="description"/>
    	<meta name="keywords" content="keywords"/>
    {%/block%}
    {%require name="common/static/style/base.less"%}
    {%require name="common/static/script/jquery.js"%}
    {%require name="common/static/script/jquery.json.js"%}
    {%require name="common/static/script/boot.js"%}
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="/static/common/static/favicon_9337045.ico"/>
    {%block name="block_head_static"%}{%/block%}
    <!--[FIS_CSS_LINKS_HOOK]-->
</head>
<body>
    {%block name="block_content"%}{%/block%}
    <!--[FIS_JS_SCRIPT_HOOK]-->
    {%if isset($data)%}
        {%script type="text/javascript"%}
            console.log({%json_encode($data)%})
        {%/script%}
    {%/if%}
</body>
{%/html%}