{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>选择进出口 - 箱典典</title>
{%/block%}

{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix bottom50">
    	{%widget name="user/widget/nav_service/nav_service.tpl" page=0%}
    	<div class="wrapper right">
            {%widget name="order/widget/view/order_choose/order_choose.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}