{if $fileType != 'xls' && $fileType != 'doc'}
	<link rel="shortcut icon" href="{$path}../../../images/favicon.ico"/>
	<link rel="stylesheet" href="{$path}../../../css/main.css">
{/if}
{if !$menu}
	<hr/>
{/if}
<h1 class="align__text-center">{$title}</h1>
{include file=$path|cat:"library/dateRangeDisplay.tpl"}
<br/>
<table class="si_report_table">
	<thead>
		<tr>
			<th>{$LANG.billerUc}</th>
			<th>{$LANG.salesUc}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $data as $biller}
		<tr class="tr_{cycle values="A,B"}">
			<td>{$biller.name|htmlSafe}</td>
			<td class="align__text-right">{$biller.sumTotal|utilCurrency|default:'-'}</td>
		</tr>
	{/foreach}
	</tbody>
	<tfoot>
	<tr>
		<td class="align__text-right">{$LANG.totalSales}:</td>
		<td class="align__text-right">{$totalSales|utilCurrency|default:'-'}</td>
	</tr>
	</tfoot>
</table>
