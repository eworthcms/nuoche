
<link rel="stylesheet" type="text/css"
	href="<?php echo base_url();?>css/userdata.css" />
<div class="Now-people">
    <p></p>
</div>
<div class="pageSession-content">
	<div class="pageSession-top">
		<div class="timeBox">
		   <form id="form" action="/record/index" method="GET">
<!--                <select name="source" id="source">-->
<!--                   <option --><?php //if($map['source'] =='' || $map['source'] =='0'){echo 'selected';} ?><!-- value="">发起发</option>-->
<!--                   <option --><?php //if($map['source'] =='1'){echo 'selected';} ?><!-- value="">挪车方</option>-->
<!--                </select>-->
                <select name="province" id="province_id" onchange="select_city();">
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

                        <td class="firstTd"><i>ID</i></td>
                        <td><i>挪车方</i></td>
						<td><i>车牌号</i></td>
						<td><i>手机号</i></td>
                        <td><i>发起方</i></td>
                        <td><i>拨打次数</i></td>
						<td><i>位置</i></td>
						<td><i>状态</i></td>
						<td><i>日期</i></td>
					</tr>
					<?php
					if (is_array ($records) && count ( $records )) {
						foreach ( $records as $key => $record ) {
							?>
							
						<tr>
                            <td class="firstTd"><?php echo $record['id'];?></td>
                            <td ><?php echo $record['nick_name'];?></td>
						    <td><?php if ($record['car_no']=='' || $record['car_no']==null){echo '暂无';}else{echo strval($record['car_no']);}?></td>
						<td><?php if ($record['mobile']=='' || $record['mobile']==null){echo '未绑定';}else{echo strval($record['mobile']);}?></td>
                            <td><?php echo $record['from_user_name']; ?></td>
                            <td><?php echo $record['clicks']; ?></td>
                            <td><?php if ($record['position']=='' || $record['position']==null){echo '---';}else{echo $record['position'];}?></td>
					    <td>    <?php if($record['status']=='0'){ echo  $status='有效';}else{ echo  $status='禁用';} ?></td>
					      <td><?php echo date('Y-m-d H:i',$record['create_time']); ?></td>
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

	});
	function select_city(){
		var province_id=$("#province_id").find("option:selected").attr('data-id');
		//城市记录
		$.ajax({  
	        type : "GET",  //提交方式  
	        url : "/record/city_list",//路径
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
</script>
