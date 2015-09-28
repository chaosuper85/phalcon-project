{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>订单管理 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl"%}
    <div class="wrapper-wide clearfix">
    	{%widget name="user/widget/nav_service/nav_service.tpl"%}
    	<div class="wraper right">
    		{%widget name="agent/widget/view/index/index.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}