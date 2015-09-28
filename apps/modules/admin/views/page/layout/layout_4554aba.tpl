<!DOCTYPE html>
{%html framework="assets/lib/mod.js"%}
<head>
    {%block name="block_head"%}
        <title>箱典典 - 管理平台</title>
    {%/block%}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/static/assets/image/favicon_32a97e2.ico"/>
    {%require name="bower_components/normalize-css/normalize.css"%}
    {%require name="assets/css/base.less"%}
    {%require name="assets/css/iconfont/iconfont.css"%}
    {%require name="bower_components/es5-shim/es5-shim.js"%}
    <!--[FIS_CSS_LINKS_HOOK]-->
    {%block name="block_head_static"%}{%/block%}
</head>
<body>
    {%block name="block_content"%}{%/block%}
    <section id="popups"></section>
    <!--[FIS_JS_SCRIPT_HOOK]-->
    {%if isset($data)%}
        {%script type="text/javascript"%}
            console.log({%json_encode($data)%})
        {%/script%}
    {%/if%}
</body>
{%/html%}