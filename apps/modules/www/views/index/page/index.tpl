{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>箱典典</title>
{%/block%}

{%block name="block_content"%}
	{%widget name="index/widget/header/header.tpl" page="home"%}
    {%widget name="index/widget/view/index/index.tpl"%} 
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}
