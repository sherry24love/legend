<table class="table">
	<tbody>
		<tr>
			<th width="100">处方详细(RP)</th>
			<td>
				@foreach( $secret as $val ) 
				<a class="btn btn-default">{{data_get( $val , 'goods_name' ) }} ({{data_get( $val , 'supplier_name') }}) &nbsp;({{data_get( $val , 'num' )}}){{data_get( $val , 'measure_unit')}}</a>
				@endforeach
			</td>
	</tbody>
</table>
