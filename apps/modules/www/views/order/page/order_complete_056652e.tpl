{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>完善订单 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix">
    	{%widget name="order/widget/view/order_complete/order_complete.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}