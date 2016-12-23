function set_cp_title()
{
	if (typeof(parent.document) != 'undefined' && typeof(parent.document) != 'unknown' && typeof(parent.document.title) == 'string')
	{
		parent.document.title = (document.title != '' ? document.title : 'Farsight Solutions');
	}
}

function show_edit_title(xid,xtitle,mod,z,l, act) {
	fetch_object(mod+'_title_edit_'+xid).innerHTML = '<input type="text" id="ctitle_'+xid+'" value="'+xtitle+'" size="'+z+'">&nbsp;<input type="button" value="'+l+'" onclick="return aj_base_title('+xid+',\''+mod+'\',\''+act+'\');">';
	return false;
}
function show_edit_title2(xid,xtitle,mod,c,z,l, act) {
	fetch_object(c+'_title_edit_'+xid).innerHTML = '<input type="text" id="ctitle_'+xid+'" value="'+xtitle+'" size="'+z+'">&nbsp;<input type="button" value="'+l+'" onclick="return aj_base_title('+xid+',\''+mod+'\',\''+act+'\');">';
	return false;
}

function aj_base_title(xid,mod,act) {
	if(act =='') { act ='quick_title'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&id='+xid+'&title='+encodeURI(aj_fetch_string(fetch_object('ctitle_'+xid).value)),'ajaxload_container', mod+'_main');
	return false;
}

function aj_base_delete(xid,mod,la,act,mid) {
	if(confirm(la)) {
		if(act =='') { act ='delete'; }
		if(mid == '') { mid ='id'; }
		ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&load_hf=1','ajaxload_container', mod+'_main');
	}
	return false;
}

function aj_base_status(xid,stat,mod,act,mid) {
	if(act =='') { act ='status'; }
	if(mid == '') { mid ='id'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&stat='+stat+'&load_hf=1','ajaxload_container', mod+'_main');
	return false;
}

function update_form_product(input){
	var catid = $(input).val();
//	window.location.href = "modules.php?f=products&do=create" + "&catid=" + catid;
	console.log("load cate id : " + catid);
	$.ajax({
		url: "modules.php?f=products&do=create" + "&catid=" + catid,
	}).done(function ( data ) {
		if( console && console.log ) {
			$('#thuoc_tinh table').replaceWith($(data).find('#thuoc_tinh').html());
		}
	}).fail(function() { alert("error"); }
	);
}
function update_edit_form_product(input,proid){
	var catid = $(input).val();
//	window.location.href = "modules.php?f=products&do=create" + "&catid=" + catid;
	console.log("load cate id : " + catid);
	$.ajax({
		  url: "modules.php?f=products&ajax=1&do=edit" + "&catid=" + catid + "&id=" + proid,
		}).done(function ( data ) {
		  if( console && console.log ) {
			  $('#thuoc_tinh table').replaceWith($(data).find('#thuoc_tinh').html());
		  }
		}).fail(function() { alert("error"); }
	    );
}

function update_form_filter(input,url){
	var catid = $(input).val();
	window.location.href = url + "&catid=" + catid;
}

function update_property_filter(input,url){
	var property_id = $(input).val();
	window.location.href = url + "&property_id_new=" + property_id;
}

function update_filter_value(input){
	var type = $(input).val();
	console.log("Select type filter: " + type);
	if(type == 0){
		$('#value_max').hide();
		$('#value_min').show();
	}else if (type == 1){
		$('#value_max').show();
		$('#value_min').show();
	}else if (type == 2){
		$('#value_max').show();
		$('#value_min').hide();
	}
}