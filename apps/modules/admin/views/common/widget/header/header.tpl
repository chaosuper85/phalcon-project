<header id="main-head">
	<span class="title">{%$title%}</span>
	<div class="head-user">
		<img src="/static/assets/image/avatar.png" alt="avatar"/>
		<span class="name">{%$data.user.username%}</span>
		<i class="iconfont icon-yfselect"></i>
		<ul class="head-user-nav">
			<li id="logout"><i class="iconfont icon-tuichu"></i>退出</li>
		</ul>
	</div>
</header>

{%script type="text/javascript"%}
	require('common/widget/header/header')();
{%/script%}