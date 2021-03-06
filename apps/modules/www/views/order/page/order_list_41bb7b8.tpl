{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>订单列表 - 箱典典</title>
{%/block%}

{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix bottom50">
    	{%widget name="user/widget/nav_service/nav_service.tpl" page=1%}
    	<div class="wrapper right">
            {%widget name="order/widget/view/order_list/order_list.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}