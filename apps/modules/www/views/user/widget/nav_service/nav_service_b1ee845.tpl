{%$active = ['','','','']%}
{%$user_info = $data.user%}
{%if isset($page)%}
	{%$active[$page] = 'active'%}
{%/if%}
{%require name="user/static/style/nav.less"%}
<ul id="user-nav">
	<div class="user-nav-wrap">
	<li class="title">订单管理</li>
		<li class="item {%$active[1]%}">
			<a href="/order/list">
				<span class="nav-icon order-list"></span>
				交易订单
			</a>
		</li>
		{%if !empty($user_info) && $user_info.usertype == 2%}
			<li class="item {%$active[0]%}">
				<a href="/freight/order/choose" class="new">
					<span class="nav-icon new"></span>
					发起订单
				</a>
			</li>
		{%/if%}
        {%if !empty($user_info) && $user_info.usertype == 1%}
            <li class="item {%$active[0]%}">
                <a href="/carteam/order/car_manage">
                    <span class="nav-icon car"></span>
                    车辆管理
                </a>
            </li>
        {%/if%}
	</div>
</ul>