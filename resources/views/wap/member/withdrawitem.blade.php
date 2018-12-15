@foreach( $withdraw->items() as $val )
    <li class="aui-list-item">
        <div class="list-state">
            <div class="shop-name"><i class="iconfont icon-youhui1"></i>&nbsp;&nbsp;{{$val->cash}}</div>
            <div class="gs-state txt-red">{{data_get( config('global.withdraw_status') , $val->status )}}</div>
        </div>
        <div class="aui-media-list-item-inner">
            <div class="aui-list-item-inner">
                
                <div class="aui-list-item-text">
                    持卡人：{{$val->card_name }}
                </div>
                
                <div class="aui-list-item-text aui-margin-t-5">
                    开户行：{{data_get( config('global.bank') , $val->card_bank_id )}}
                </div>
                <div class="aui-list-item-text aui-margin-t-5">
                    卡号：{{ $val->card_no }}
                </div>
                
            </div>
        </div>
        <div class="aui-info f12" style="padding-top:0">
            <div class="aui-info-item aui-font-size-14">
                {{$val->created_at}}
            </div>
            <div class="btn-box">
            @if( $val->status == 0 )
                <div class="aui-btn aui-btn-danger aui-btn-outlined btn-detail" onclick="cancel ( this )" data-href="{{route('wap.withdraw.cancel' , ['id' => $val->id ])}}">取消提现</div>

            @endif

            @if( $val->status == 2 )
                <div class="aui-btn aui-btn-danger aui-btn-outlined btn-reason" onclick="reason(this)" data-reason="{{ $val->remark }}">查看原因</div>
            @endif
            
            @if( $val->status == 4 )
                <div class="aui-btn aui-btn-danger aui-btn-outlined btn-reason" onclick="reason(this)" data-reason="用户自行取消提现">查看原因</div>
            @endif
            </div>
        </div>
    </li>
@endforeach