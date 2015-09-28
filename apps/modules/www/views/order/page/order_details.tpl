{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>订单详情 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}

{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix">
    	{%widget name="order/widget/view/order_details/order_details.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}