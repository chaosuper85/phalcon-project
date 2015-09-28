{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>登录 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_content"%}
    {%widget name="index/widget/header/header.tpl"%}
    {%widget name="index/widget/view/login/login.tpl"%}
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}
