{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>发起订单 - 箱典典</title>
{%/block%}

{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix bottom50">
    	{%widget name="order/widget/view/order_new/order_new.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}