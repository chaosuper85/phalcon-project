{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>忘记密码-箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_content"%}
    {%widget name="index/widget/header/header.tpl"%}
    {%widget name="index/widget/view/forgotPassword/forgot_pwd.tpl"%}
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}