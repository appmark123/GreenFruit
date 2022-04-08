var http_req = false;
function cmpPOSTRequest(url, parameters) {
	http_req = false;
  if (window.XMLHttpRequest) { // Mozilla, Safari,...
  	http_req = new XMLHttpRequest();
  	if (http_req.overrideMimeType) {
		// set type accordingly to anticipated content type
		//http_req.overrideMimeType('text/xml');
		http_req.overrideMimeType('text/html');
	}
  } else if (window.ActiveXObject) { // IE
  	try {
  		http_req = new ActiveXObject("Msxml2.XMLHTTP");
  	} catch (e) {
  		try {
  			http_req = new ActiveXObject("Microsoft.XMLHTTP");
  		} catch (e) {}
  	}
  }
  if (!http_req) {
  	alert('无法创建XMLHTTP类实例');
  	return false;
  }
  //alert(parameters);
  http_req.onreadystatechange = cmpContents;
  http_req.open('POST', url, true);
  http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http_req.setRequestHeader("Content-length", parameters.length);
  http_req.setRequestHeader("Connection", "close");
  http_req.send(parameters);
}

function cmpContents() 
{
  //alert(http_req.responseText);
  if (http_req.readyState == 4) 
  {
  	if (http_req.status == 200) 
  	{
		//alert(http_req.responseText);
		if(http_req.responseText == "验证码输入错误")
		{
			alert(http_req.responseText);
			result = http_req.responseText;
			document.getElementById('cmp_alertmessage').innerHTML = result;
			document.getElementById("cmp_captcha").value = "";
		}
		else if(http_req.responseText == "请刷新页面再次尝试")
		{
			alert(http_req.responseText);
			result = http_req.responseText;
			document.getElementById('cmp_alertmessage').innerHTML = result;
			document.getElementById("cmp_captcha").value = "";
		}
		else
		{
			alert(http_req.responseText);
			result = http_req.responseText;
			document.getElementById('cmp_alertmessage').innerHTML = result;   
			document.getElementById("cmp_email").value = "";
			document.getElementById("cmp_name").value = "";
			document.getElementById("cmp_message").value = "";
			document.getElementById("cmp_captcha").value = "";
		}
	} 
	else 
	{
		alert('请求出错了');
	}
}
}

function cmp_submit(obj,url) 
{
	
	
	_e=document.getElementById("cmp_email");
	_n=document.getElementById("cmp_name");
	_m=document.getElementById("cmp_message");
	_c=document.getElementById("cmp_captcha");
	
	if(_n.value=="")
	{
		alert("请输入您的名字");
		_n.focus();
		return false;    
	}
	else if(_e.value=="")
	{
		alert("请输入邮箱地址");
		_e.focus();
		return false;    
	}
	else if(_e.value!="" && (_e.value.indexOf("@",0)==-1 || _e.value.indexOf(".",0)==-1))
	{
		alert("请输入一个有效的邮箱地址")
		_e.focus();
		_e.select();
		return false;
	} 
	else if(_m.value=="")
	{
		alert("请输入您要发送的内容");
		_m.focus();
		return false;    
	}
	else if(_c.value=="")
	{
		alert("请输入验证码");
		_c.focus();
		return false;    
	}

	document.getElementById('cmp_alertmessage').innerHTML = "正在发送..."; 
	
	var str = "cmp_name=" + encodeURI( document.getElementById("cmp_name").value ) + "&cmp_email=" + encodeURI( document.getElementById("cmp_email").value ) + "&cmp_message=" + encodeURI( document.getElementById("cmp_message").value ) + "&cmp_captcha=" + encodeURI( document.getElementById("cmp_captcha").value );
	
	cmpPOSTRequest(url+'sendmail.php', str);
}
