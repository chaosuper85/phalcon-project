{%extends file="common/page/layout.tpl"%}

{%block name="block_head_seo"%}
	<title>车辆管理 - 箱典典</title>
	<meta name="description" content="description"/>
    <meta name="keywords" content="keywords"/>
{%/block%}
{%block name="block_head_static"%}
    {%require name="user/static/style/common.less"%}
{%/block%}
{%block name="block_content"%}
    {%widget name="user/widget/header/header.tpl" page=0%}
    <div class="wrapper-wide clearfix">
    	{%widget name="user/widget/nav_service/nav_service.tpl" page=0%}
    	<div class="wrapper right">
            {%widget name="order/widget/view/car_manage/car_manage.tpl"%}
    	</div>
    </div>
    {%widget name="common/widget/footer/footer.tpl"%}
{%/block%}