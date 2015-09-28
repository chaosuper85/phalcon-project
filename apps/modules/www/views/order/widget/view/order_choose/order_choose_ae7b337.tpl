{%widget name="user/widget/header_content/header_content.tpl" title="选择进出口" class="new"%}
<div id="order-choose" class="user-content">
    <div class="order-content">
        <div class="docks clearfix">
            <div class="title">起运城市：</div>
            <div id="docks-selector"></div>
        </div>
		<div class="choose_type clearfix">
			<div class="choose_wrap">
				<a href="javascript:;" class="user-btn current" id="choose-out">出口</a>
			</div>
            <div class="choose_wrap">
                <a href="javascript:;" class="user-btn disable" id="choose-in" title="暂未开放">进口</a>
            </div>
        </div>
        <div class="btn-container">
            <a href="/freight/order/new" class="user-btn">确定</a>
        </div>

    </div>
</div>

{%script type="text/javascript"%}
	require('order/widget/view/order_choose/order_choose').init();
{%/script%}