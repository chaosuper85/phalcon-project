{%extends file="page/layout/layout.tpl"%}

{%block name="block_head"%}
    <title>登录 - 箱典典管理平台</title>
{%/block%}

{%block name="block_head_static"%}
	{%require name="bower_components/jquery/dist/jquery.min.js"%}
{%/block%}

{%block name="block_content"%}
	{%widget name="index/partial/login/login.tpl"%}
{%/block%}
