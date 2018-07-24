
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>css/userdata.css" />
<!----弹出弹窗----->
<div class="pop-up-windows">
	<i class="icon iconfont icon-guanbi"></i>
	<h5>编辑</h5>
	<p>
		<span>&nbsp;&nbsp;挪车二维码：</span> <i id="user_qrcode_no"><?php echo $user_total; ?></i> <input
			type="button" value="重置" />
	</p>
	<p>
		<span>微信openID：</span> <i id="user_open_id">5000088</i>
	</p>
	<p>
		<span>&nbsp;&nbsp;二维码来源：</span> <i>地推</i>
	</p>
	<input type="text" id="user_mobile"  class="tel-input" placeholder="手机号码：13994936348" />
	<input type="text" class="carNum-input" id="user_car_no" placeholder="车牌号：京A 1539" />
	<input type="hidden" value="" id="post_user_id">
	<p>
		<button class="cancel-pop-up">取消</button>
		<button class="change-pop-up">修改</button>
	</p>
</div>
<div class="pageSession-content">
	<div class="pageSession-top">
		<div class="Now-people">
			<p>
				目前注册用户为<span> <?php echo $user_total;?> </span>,已绑定二维码的用户为<span> <?php echo $qrcode_total;?> </span>
			</p>
		</div>
		<div class="timeBox">
		   <form id="form" action="/user/index" method="GET">
		   
			<select name="source" id="source">
			   <option <?php if($map['source'] ==''){echo 'selected';} ?> value="">全部</option>
				<option <?php if($map['source'] =='2'){echo 'selected';} ?>  value="2">地推</option>
				<option <?php if($map['source'] =='0'){echo 'selected';} ?> value="0">自主关注</option>
				<option  <?php if($map['source'] =='1'){echo 'selected';}?> value="1">扫码</option>
			</select> <select name="province" id="province_id"
				onchange="select_city();">
				<option value="">全部</option>
				<?php
				
				foreach ( $city_list as $key => $city ) {
					?>
					<option data-id="<?php echo $city['region_id'];?>"
					value="<?php echo $city['region_name'];?>"><?php echo $city['region_name'];?></option>
					<?php
				}
				?>
			</select>
			 <select name="city" id="city_id">
				<option value="">全部</option>

			</select> <label for="from">从</label> <input type="text" value="<?php echo $map['from'];?>" id="from"
				name="from"> <label for="to">-</label> <input type="text" id="to"
				name="to" value="<?php echo $map['to'];?>"> <input type="text" name="user_id" id="user_id" value="<?php echo $map['user_id']?>"
				placeholder="请输入关键词"> <button type="submit" class="search-btn icon iconfont icon-tubiao17">搜索</button>
			</form>
		</div>

	</div>
	<!-------挪车列表部分-------->
	<div id="dataBox">
		<!--列表部分开始-->
		<div class="ticket-list  oldTelList">
			<table border="">
				<tbody>
					<tr>
						<td class="firstTd"><i>用户ID</i></td>
						<td><i>二维码来源</i></td>
						<td><i>昵称</i></td>
						<td><i>手机号</i></td>
						<td><i>车牌号</i></td>
						<td><i>状态</i></td>
						<td><i>挪车二维码</i></td>
						<td><i>关注日期</i></td>
						<td><i>注册日期</i></td>
						<td><i>操作</i></td>
					</tr>
					<?php
					if (is_array ( $users ) && count ( $users )) {
						foreach ( $users as $key => $user ) {
							?>
							
						<tr>
						<td class="firstTd"><?php if ($user['open_id']=='' || $user['open_id']==null){echo '未绑定';}else{echo $user['id'];}?></td>
						<td><?php if ($user['source']==null){echo '关注';}elseif($user['source']==0){echo '线上';}elseif ($user['source']=='1' || $user['source']=='2'){echo '地推';}else{echo '关注';}?></td>
						<td><?php if ($user['nick_name']=='' || $user['nick_name']==null){echo '暂无';}else{echo strval($user['nick_name']);}?></td>
						<td><?php if ($user['mobile']=='' || $user['mobile']==null){echo '未绑定';}else{echo strval($user['mobile']);}?></td>
						<td><?php if ($user['car_no']=='' || $user['car_no']==null){echo '未绑定';}else{echo strval($user['car_no']);}?></td>
						<td><?php if ($user['no']=='' || $user['no']==null){echo '已关注';}else{echo '已注册';}?></td>
						<td class="erweima-td">
							<?php  if ($user['no']=='' || $user['no']==null){ ?>
							<a href="#">暂无(二维码编码)</a>
							<?php
							} else {
								?>
							
							<a href="<?php echo base_url()."uploads/qrcode/".$user['no'].".jpg"; ?>" target="_blank"><?php echo $user['no'];?>(二维码编码)</a>
							<?php
						   }
						?>
							
						</td>
						<td><?php echo date('Y-m-d H:i',$user['subscribe_time']);?></td>
						<td><?php echo date('Y-m-d H:i',$user['create_time']); ?></td>
						<?php if($user['status']=='0'){ $status='有效';}else{ $status='禁用';} ?>
						<td><?php if ($user['no']=='' || $user['no']==null){echo '无效';}else{echo "<span data-id= '{$user['id']}'class='compile'>编辑</span> |<span data-id='{$user['id']}' data-status='{$user['status']}' class='do_status'>{$status}</span>";}?></td>	
					</tr>
							<?php
						}
					}else{?>
						<tr>
							<td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
						</tr>
					<?php
					}
					?>
					
					
				</tbody>
			</table>
		</div>
		<div class="pageNum">
			<em>共<?php echo $total_page;?>页</em><?php echo $page;?>
		</div>
	</div>
	<script type="text/javascript">
	$(function(){
		$( "#from" ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      numberOfMonths: 1,
	      onClose: function( selectedDate ) {
	        $( "#to" ).datepicker( "option", "minDate", selectedDate );
	      }
	    });
	    $( "#to" ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      numberOfMonths: 1,
	      onClose: function( selectedDate ) {
	        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
	      }
	    });
	    //点击编辑按钮遮罩层出现，出现弹出
	    $(".compile").click(function(){
		    var data_id=$(this).attr('data-id');
		    if(data_id !='' && typeof(data_id) !='undefined'){
		    	$.ajax({  
			        type : "GET",  //提交方式  
			        url : "/user/user_info",//路径  
			        dataType:'json',
			        data:{
			        	user_id:data_id,
					},
			        //数据，这里使用的是Json格式进行传输  
			        success : function(result) {//返回数据根据结果进行相应的处理  
			           var data=result.data;
			           var user_open_id =data.open_id;
			           var user_qrcode_no=data.no;
			           var user_car_no=data.car_no;
			           var user_mobile=data.mobile;
			           var source=data.source;
					   var user_source='未知';
			           if(source==0){
			        	   user_source='自主关注';
				       }else if(source==1){
				    	   user_source='扫码';
					   }else if(source==2){
						   user_source='地推';
					   }
			           $("#user_qrcode_no").text(user_qrcode_no);
			           $("#user_open_id").text(user_open_id);
			           $("#user_source").text(user_source);
			           $("#user_mobile").val(user_mobile);
			           $("#user_car_no").val(user_car_no);
			           $("#post_user_id").val(data.id);
			           
			           $("#mark").show();
				       $(".pop-up-windows").show();
			  	    }  
			    });
			}
	    	
	    });
	    $(".icon-guanbi").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    })
	    //点击取消按钮
	    $(".cancel-pop-up").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    })
	    //点击修改
	    $(".change-pop-up").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    });
	    //二维码出现；	    
	    $(".ticket-list table td:nth-child(6)").each(function(index){
			$(".ticket-list table td:nth-child(6)").eq(index).mouseover(function(){
				$(".erweima-box").eq(index - 1).show();
			}).mouseout(function(){
				$(".erweima-box").eq(index - 1).hide();
			})			
		});
	    
	});
	$('.do_status').click(function (){
		var user_id=$(this).attr('data-id');
		var status=$(this).attr('data-status');
		$.ajax({  
	        type : "post",  //提交方式  
	        url : "/user/do_status",//路径  
	        dataType:'json',
	        data:{
	        	user_id:user_id,
	        	status:status,
			},
	        //数据，这里使用的是Json格式进行传输  
	        success : function(result) {//返回数据根据结果进行相应的处理  
	           window.location.reload(true);
	  	    }  
	    });
	});
	function select_city(){
		var province_id=$("#province_id").find("option:selected").attr('data-id');
		//城市记录
		$.ajax({  
	        type : "GET",  //提交方式  
	        url : "/user/city_list",//路径  
	        dataType:'json',
	        data:{
	        	province_id:province_id,
			},
	        //数据，这里使用的是Json格式进行传输  
	        success : function(result) {//返回数据根据结果进行相应的处理  
	           var data=result.data;
	           $("#city_id").html(data);
	  	    }  
	    });
	}
	$(".change-pop-up").click(function (){
		var user_mobile=$("#user_mobile").val();
		var user_car_no=$("#user_car_no").val();
		var post_user_id=$("#post_user_id").val();
		$.ajax({  
	        type : "POST",  //提交方式  
	        url : "/user/save_user",//路径  
	        dataType:'json',
	        data:{
	        	mobile:user_mobile,
	        	car_no:user_car_no,
	        	user_id:post_user_id
			},
	        //数据，这里使用的是Json格式进行传输  
	        success : function(result) {//返回数据根据结果进行相应的处理  
				window.location.reload(true);
	  	    }  
	    });
	
	});
</script>