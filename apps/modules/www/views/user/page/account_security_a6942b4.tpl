{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>账户安全 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page="2"%}
    <div class="wrapper-wide clearfix">
    	{%widget name="user/widget/nav_user/nav_user.tpl" title="我的公司" page="2"%}
    	<div class="wrapper right">
    		{%widget name="user/widget/view/account_security/account_security.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}