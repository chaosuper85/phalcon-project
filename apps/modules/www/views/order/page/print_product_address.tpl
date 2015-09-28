{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>打印产装联系单 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix">
		{%widget name="order/widget/view/print_product_address/print_product_address.tpl"%}
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}