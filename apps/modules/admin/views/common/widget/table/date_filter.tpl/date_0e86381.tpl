{%$param = $data.paras%}

<div class="date-filter">
	<span class="title">筛选</span>
	<div id="filter_time_start"></div>
	<span class="flag">to</span>
	<div id="filter_time_end"></div>
</div>

{%script type="text/javascript"%}
	require('common/widget/table/date_filter.tpl/date').init({%json_encode($param)%},'{%$from%}','{%$to%}');
{%/script%}