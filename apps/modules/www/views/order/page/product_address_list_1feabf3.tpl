{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>产装联系单列表 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix">
    		{%widget name="order/widget/view/product_address_list/product_address_list.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}