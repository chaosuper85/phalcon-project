{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>物流详情 - 箱典典</title>
{%/block%}

{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix bottom50">
    		{%widget name="order/widget/view/order_trace/order_trace.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}