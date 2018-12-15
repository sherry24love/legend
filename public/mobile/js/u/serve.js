var provinces_id = '';
var city_id = '';
var area_id = '';

var toast = new auiToast();

    (function($, doc) {
		$.init();
		$.ready(function() {
			//					//级联示例
			var cityPicker3 = new $.PopPicker({
				layer: 3
			});
			cityPicker3.setData(cityData3);
			var showCityPickerButton = doc.getElementById('showCityPicker3');
			var cityResult3 = doc.getElementById('cityResult3');
			showCityPickerButton.addEventListener('tap', function(event) {
				cityPicker3.show(function(items) {
				 	provinces_id = items[0].value;
					city_id = items[1].value;
					area_id = items[2].value;
					pcaval();
					cityResult3.innerText = (items[0] || {}).text + " " + (items[1] || {}).text + " " + (items[2] || {}).text;
					//返回 false 可以阻止选择框的关闭
					//return false;
				});
			}, false);
		});
	})(mui, document);
	
	function pcaval(){
	 	$('#provinces_id').val(provinces_id);
    	$('#city_id').val(city_id);
    	$('#area_id').val(area_id);
		}
	
   var skillstr = [];

   $(document).on('tap' , '.skill_id' , function(){
   	if($(this).hasClass('cur')){
   			$(this).removeClass('cur');
   			skillstr.pop($(this).attr('skill_id'));
   		}else{
   			$(this).addClass('cur');
   			skillstr.push($(this).attr('skill_id'));
   			}
	$('#skill_id').val(skillstr);
   });

   
   //隐藏性别选择框
//	   var s_open = '<?php //echo $serviceInfo['s_type'];?>';
//	   if( s_open == 1 ){
//	   	$('.sex').hide();
//	   }
 	$(document).on('tap' , '.s_type' , function(){
		   	if($(this).children('input').val() == 0){
		   		$('.sex').show();
		   	}
		   	if($(this).children('input').val() == 1){
		   		$('.sex').hide();
		   	}
	   });

 	$(document).on('tap' , '.js_addservide' , function(){
			//点击申请服务商信息
			var service_name = $('#service_name').val();
			var skill_id = $('#skill_id').val();//选择的技能
			var s_type =  $('input[name="s_type"]').filter(':checked').val();//个人/公司
			var sex = $('input[name="sex"]').filter(':checked').val();//性别
			var introduce = $('#introduce').val();//介绍
			var price = $('#price').val();//服务价格
			var mobile = $('#mobile').val();//联系电话
			var address = $('#address').val();//地址
			if(!service_name){
				toast.fail({title:"请输入服务商名称!"});
				$("#service_name").focus(); 
				return false;
				}
			if(!skill_id){
				toast.fail({title:"请选择技能类型!"});
				$("#skill_id").focus(); 
				return false;
				}
			if(s_type == null){
				toast.fail({title:"请选择个人/企业!"});
				$("#s_type").focus(); 
				return false;
				}
			if(s_type == 0){
				if(sex == null){
					toast.fail({title:"请选择性别!"});
					$("#sex").focus(); 
					return false;
					}
				}
			if(!introduce){
				toast.fail({title:"请填写介绍!"});
				$("#introduce").focus(); 
				return false;
				}
			if(!price){
				toast.fail({title:"请输入服务价!"});
				$("#price").focus(); 
				return false;
				}
			
			if(isNaN(price)){
				toast.fail({title:"请输入正确格式的服务价!"});
				$("#price").focus(); 
				return false;
				}
			if(!mobile){
				toast.fail({title:"请输入手机号!"});
				$("#mobile").focus(); 
				return false;
			}
			var myreg = /^(((17[0-9]{1})|(13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
			if(!myreg.test($("#mobile").val())) 
			{ 
				toast.fail({title:"手机号码格式不正确！"});
				$("#mobile").focus(); 
				return false; 
			} 
			if(!provinces_id || !city_id || !area_id){
				toast.fail({title:"请选择省市区!"});
				$("#provinces_id").focus(); 
				return false;
			}
			if(!address){
				toast.fail({title:"请填写详细地址!"});
				$("#address").focus(); 
				return false;
			} 
			
			$.post('/index/serve',{service_name:service_name,skill_id:skill_id,s_type:s_type,introduce:introduce,price:price,mobile:mobile,provinces_id:provinces_id,city_id:city_id,area_id:area_id,address:address,sex:sex},function( res ){
				if( res.errcode == 0 ){
					toast.fail({title:res.msg});
					location.href = res.data;
				}else{
					toast.fail({title:res.msg});
				}
			 
			},'json');
	   });
	