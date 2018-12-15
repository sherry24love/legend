<div id="footer">
    <div class="center_content_footer footer_content">
        <div class="footer_middle">
            <div class="footer_guide">
                <span>快速导航</span>
                <ul>
                    <li><a href="{{route('track')}}">货物追踪</a></li>
                    <li><a href="{{route('checkin')}}">我要下单</a></li>
                    <li><a href="{{route('singlepage' , ['id' => 8 ])}}">常见问答</a></li>
                    {{--<li><a href="{{route('singlepage' , ['id' => 7 ])}}">奖励规则</a></li>--}}
                    <li><a href="{{route('singlepage' , ['id' => 11 ])}}">用户手册</a></li>
                </ul>
            </div>
            <div class="footer_guide">
                <span>关于我们</span>
                <ul>
                    <li><a href="{{route('singlepage' , ['id' => 1 ])}}">公司简介</a></li>
                    <li><a href="{{route('singlepage' , ['id' => 5 ])}}">服务团队</a></li>
                    <li><a href="{{route('singlepage' , ['id' => 6 ])}}">服务范围</a></li>
                    <li><a href="{{route('singlepage' , ['id' => 2 ])}}">联系我们</a></li>
                    <li><a href="{{route('singlepage' , ['id' => 9 ])}}">人才招聘</a></li>
                </ul>
            </div>
            <div class="footer_guide">
                <span>行业资讯</span>
                <ul>
                    <li><a href="{{route('posts' , ['id' => 4 ])}}">最新优惠</a></li>
                    <li><a href="{{route('posts' , ['id' => 7 ])}}">航线推介</a></li>
                    <li><a href="{{route('posts' , ['id' => 6 ])}}">公司资讯</a></li>
                    <li><a href="{{route('posts' , ['id' => 5 ])}}">行业资讯</a></li>
                </ul>
            </div>
            <div class="contact_tel">
                <p style="font-size: 20px;margin-bottom: 15px;">联系方式</p>
                <p style="font-size: 16px;margin-bottom: 22px;">0596-6859322</p>
                <a class="qq_but" id="foot-qq-serve" >企业QQ</a>
            </div>
            <div class="contact_tel QR_code">
                <img src="{{asset('img/QR_code.jpg')}}" style="width:100px;" />
                <p style="text-align: center;">扫一扫 关注我们</p>
            </div>
        </div>
    </div>
</div>
<div class="footer_bottom">
    <div class="bottom_logo">
        Copyright@2017 富裕通船运 All Right Reserved 版权所有 粤ICP备0723767831号
    </div>
</div>
