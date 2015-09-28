<div class="banner-bg">
	<img src="/static/index/static/image/bg-banner_750412b.jpg"/>
</div>
<div id="home">
	<div class="banner-content clearfix">
		<div class="panel">
			<div class="panel-content clearfix">
				{%if 1 || empty($data.user)%}
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
					<!-- @TODO 已登录状态 -->
				{%/if%}
			</div>
			<div class="panel-bg"></div>
		</div>
	</div>
	<div class="content">
		<div class="top wrapper">
			<h3>箱典典<span class="slogan">让集装箱物流更简单</span></h3>
			<div class="descriptions clearfix">
				<div class="item">
					<div class="pics pic1"></div>
					<p><span>一键派单</span><span>全程掌控</span></p>
				</div>
				<div class="item">
					<div class="pics pic2"></div>
					<p><span>货物跟踪</span><span>实时监控</span></p>
				</div>
				<div class="item">
					<div class="pics pic3"></div>
					<p><span>成本优化</span><span>高效透明</span></p>
				</div>
			</div>
		</div>
		<div class="bottom">
			<div class="wrapper clearfix">
				<div class="item">
					<img class="app-pic" src="/static/index/static/image/app-pic_9aeadd5.png" alt="箱典典司机端APP">
				</div>
				<div class="item">
					<div class="app">
						<img src="/static/index/static/image/applogo_f7d772d.png" alt="箱典典司机端APP">
						<p class="title">箱典典</p>
						<div class="description">
							<p>箱典典 司机端APP</p>
							<p>随时给您的司机派单</p>
							<p>实时追踪货物的运输情况</p>
						</div>
					</div>
				</div>
				<div class="item">
					<div class="download">
						<h4>扫一扫，即可下载</h4>
						<div class="erweima">
							<img src="/static/index/static/image/erweima_1ca81b5.png" alt="">
						</div>
						<a href="javascript:;">Android下载</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>