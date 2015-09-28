<div class="banner-bg">
	<img src="/static/index/static/image/bg-banner_750412b.jpg"/>
</div>
<div id="home">
	<div class="banner-content clearfix">
		<div class="panel">
			<div class="panel-content">
				{%if empty($data.user)%}
					<h3><a href="/index/login">已有帐号？立即登录</a></h3>
					<a href="/user/register?type=freight_agent" class="btn agent">
						<span></span>
						货代注册
					</a>
					<a href="/user/register?type=carteam" class="btn carteam">
						<span></span>
						车队注册
					</a>
				{%else%}
					已登录
				{%/if%}
			</div>
			<div class="panel-bg"></div>
		</div>
	</div>
	<div class="content">
		<div class="wrapper">
			<div class="notice">
				<p>箱典典<span>让集装箱物流更简单</span></p>
			</div>
			<div class="picture-notice">
				<div class="picture-content clearfix">
					<div class="picture-order"></div>
					<p class="order">一键派单 <span>全程掌控</span></p>
				</div>
				<div class="picture-content clearfix">
					<div class="picture-trace"></div>
					<p class="trace">一键派单 <span>全程掌控</span></p>
				</div>
				<div class="picture-content clearfix">
					<div class="picture-optimize"></div>
					<p class="optimize">一键派单 <span>全程掌控</span></p>
				</div>

		<!-- 		<ul>
					<li>
						<div class="picture-order"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
					<li>
						<div class="picture-trace"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
					<li>
						<div class="picture-optimize"></div>
						<p>一键派单 <span>全程掌控</span></p>
					</li>
				</ul> -->
			</div>
		</div>
		<div class="bottom-banner">
			<div class="bottom-content">
				<div class="left-content clearfix"></div>
				<div class="center-content clearfix">
					<div class="xdd-logo">
						<div class="logo"></div>
						<p>箱典典</p>
					</div>
					<div class="app-notice">
						<p class="title">司机版APP</p>
						<p>随时给您的司机派单</p>
						<p>事实追踪货物的运输情况</p>
					</div>
				</div>
				<div class="right-content clearfix">
					<p>扫一扫，即可下载</p>
					<div class="quickmark"></div>
					<a href="javascript:;">Android下载</a>
				</div>
			</div>
		</div>
	</div>
</div>

{%script type="text/javascript"%}
	require('index/widget/view/index/index').init();
{%/script%}