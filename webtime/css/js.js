if(window.addEventListener){
    window.addEventListener("load",mladdevents,false);
}
else if(window.attachEvent){
    window.attachEvent("onload",mladdevents);
}
else{
	window.onload = mladdevents;
}
function mladdevents(){
	if(!window.mlrunShim){
		window.mlrunShim = false;
	}
	if(window.mlrunShim == true){
		var Iframe = document.createElement("iframe");
		Iframe.setAttribute("src","about:blank");
		Iframe.setAttribute("scrolling","no");
		Iframe.setAttribute("frameBorder","0");
	}
	var effects_a = new Array();
	var divs = document.getElementsByTagName('div');
	for(var j=0;j<divs.length;j++){
		if(divs[j].className.indexOf('mlmenu') != -1){
			divs[j].className = divs[j].className.replace('mlmenu','mlmenujs');
			divs[j].getElementsByTagName('ul')[0].className = divs[j].className;//just for reference
			var lis = divs[j].getElementsByTagName('li');
			for(var i =0;i<lis.length;i++){
				lis[i].onmouseover = mlover;
				lis[i].onmouseout = mloutSetTimeout;
				if(window.mlrunShim == true){
					lis[i].appendChild(Iframe.cloneNode(false));
				}
				if(lis[i].parentNode.getElementsByTagName('li')[0] == lis[i]){
					lis[i].getElementsByTagName('a')[0].className += 'first';
					if(lis[i].parentNode.className.indexOf('mlmenujs') != -1){
						lis[i].className += 'pixelfix';
					}
				}
				if(lis[i] == findLast(lis[i].parentNode)){
					lis[i].className += 'last';
				}
				if(lis[i].getElementsByTagName('ul').length > 0){
					lis[i].className += ' haschild';
					if(divs[j].className.indexOf('arrow') != -1){
						if(divs[j].className.indexOf('vertical') != -1 || lis[i].parentNode.parentNode.nodeName != 'DIV'){
							lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="vert"></span>';
						}
						else{
							lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="horiz">&darr;</span>';
						}
					}
					else if(divs[j].className.indexOf('plus') != -1){
						lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="plus">+</span>';
					}
				}
				else{
					if(divs[j].className.indexOf('arrow') != -1){
						//This accounts for a wierd IE-specific bug in horizontal menus. CSS will set visibility: hidden;. This keeps the menu level(in IE)
						lis[i].getElementsByTagName('a')[0].innerHTML += '<span class="noshow">&darr;</span>';
					}
				}
				var uls = lis[i].getElementsByTagName('ul');
				for(var k=0;k<uls.length;k++){
					var found = 'no';
					for(var z=0;z<effects_a.length;z++){
						if(effects_a[z] == uls[k]){
							found = 'yes';
						}
					}
					if(found == 'no'){
						effects_a[effects_a.length] = uls[k];
						uls[k].style.zIndex = '100';
						mlEffectLoad(uls[k]);
					}
				}
			}
		}
	}
}
function mloutSetTimeout(e){
	if(!e){
		var the_e = window.event;
	}
	else{
		var the_e = e;
	}
	var reltg = (the_e.relatedTarget) ? the_e.relatedTarget : the_e.toElement;
	if(reltg){
		var under = ancestor(reltg,this);
		if(under === false && reltg != this){
			window.mlLast = this;
			var parent = this.parentNode;
			while(parent.parentNode && parent.className.indexOf('mlmenujs') == -1){
				parent = parent.parentNode;
			}
			if(parent.className.indexOf('delay') != -1){
				window.mlTimeout = setTimeout(function(){mlout()},1000);
			}
			else{
				mlout();
			}
		}
	}
}
function mlout(){
	if(window.mlLast==null)return false;
	var links = window.mlLast.getElementsByTagName('a');
	for(var i = 0;i<links.length;i++){
		links[i].className = links[i].className.replace('hover','');
	}
	var uls = window.mlLast.getElementsByTagName('ul');
	var sib;
	for(var i=0;i<uls.length;i++){
		mlEffectOut(uls[i]);
		window.mlLast.className += ' hide';
		window.mlLast.className = window.mlLast.className.replace('hide hide','hide');
		if(window.mlrunShim == true){
			sib = uls[i];							
			while(sib.nextSibling && sib.nodeName != 'IFRAME'){
				sib = sib.nextSibling
			}
			sib.style.display = 'none';
		}
	}
	window.lastover = null;
	return true;
}
function mlover(e){
	if(!e){
		var the_e = window.event;
	}
	else{
		var the_e = e;
	}
	the_e.cancelBubble = true;
	if(the_e.stopPropagation){
		the_e.stopPropagation();
	}
	clearTimeout(window.mlTimeout);
	if(window.mlLast && window.mlLast != this && ancestor(this,window.mlLast) === false){
		mlout();
	}
	else{
		window.mlLast = null;
	}
	var reltg = (the_e.relatedTarget) ? the_e.relatedTarget : the_e.fromElement;
	var under = ancestor(reltg,this);
	if(under == false){
		var hovered = byClass(this.parentNode,'hover','a');
		for(var i=0;i<hovered.length;i++){
			if(ancestor(this,hovered[i].parentNode) == false){
				window.mlLast = hovered[i].parentNode;
				mlout();
			}
		}
		var ob = this.getElementsByTagName('ul');
		var link = this.getElementsByTagName('a')[0];
		link.className += ' hover';
		if(ob[0]){
			if(window.lastover != ob[0]){
				if(window.mlrunShim == true){
					var sib = ob[0];
					while(sib.nextSibling && sib.nodeName != 'IFRAME'){
						sib = sib.nextSibling
					}
					ob[0].style.display = 'block';
					sib.style.top = ob[0].offsetTop+'px';
					sib.style.left = ob[0].offsetLeft-2+'px';
					sib.style.width = ob[0].offsetWidth+2+'px';
					sib.style.height = ob[0].offsetHeight-2+'px';
					sib.style.display = 'block';
				}
				this.className = this.className.replace(/hide/g,'');
				mlEffectOver(ob[0],this);
				window.lastover = ob[0];
			}
		}
	}
}
function mlSetOpacity(ob,level){
	if(ob){
		var standard = level/10;
		var ie = level*10;
		ob.style.opacity = standard;
		ob.style.filter = "alpha(opacity="+ie+")"
	}
}
function mlIncreaseOpacity(ob){
	var current = ob.style.opacity;
	current = current * 10;
	var upone = current + 1;
	mlSetOpacity(ob,upone);
}
function mlIncreaseHeight(ob){
	var current = parseInt(ob.style.height);
	if(!isNaN(current)){
		var newh = current + 1;
		ob.style.height = newh+'px';
	}
}
function mlIncreaseWidth(ob){
	var current = parseInt(ob.style.width);
	if(!isNaN(current)){
		var newh = current + 1;
		ob.style.width = newh+'px';
	}
}
function mlShake(ob){
	var newp = '5px';
	var old = '';
	if(ob.style.paddingLeft==old){
		ob.style.paddingLeft=newp;
	}
	else{
		ob.style.paddingLeft=old;
	}
}
function mlEffectOver(ob,parent){
	switch(ob.className){
	case 'fade':
		ob.style.display = 'block';
		for(var i = 1;i<=10;i++){
			window.fadeTime = setTimeout(function(){mlIncreaseOpacity(ob)},i*50);
		}
		setTimeout(function(){ob.style.filter = ''},500);
		break;
	case 'shake':
		ob.style.display = 'block';
		var shake = setInterval(function(){mlShake(ob)},50);
		setTimeout(function(){clearInterval(shake)},510);
		break;
	case 'blindv':
		ob.style.display = 'block';
		if(ob.offsetHeight){
			var height = ob.offsetHeight
			ob.style.height = '0px';
			ob.style.overflow = 'hidden';
			for(var i=0;i<height;i++){
				setTimeout(function(){mlIncreaseHeight(ob)},i*3);
			}
			setTimeout(function(){ob.style.overflow='';ob.style.height='165'},(height-1)*3)//-1 for IE
		}
		break;
	case 'blindh':
		ob.style.display = 'block';
		if(ob.offsetWidth){
			var width = ob.offsetWidth;
			ob.style.width = '0px';
			ob.style.overflow = 'hidden';
			for(var i=0;i<width;i++){
				setTimeout(function(){mlIncreaseWidth(ob)},i*3);
			}
			setTimeout(function(){ob.style.overflow='visible';},width*3)
		}
		break;
	default:
		ob.style.display = 'block';
		break;
	}
}
function mlEffectOut(ob){
	switch(ob.className){
	case 'fade':
		clearTimeout(window.fadeTime);
		mlSetOpacity(ob,0);
		ob.style.display = 'none';
		break;
	case 'shake':
		ob.style.paddingLeft = '';
		ob.style.display = 'none';
		break;
	default:
		ob.style.display = 'none';
		break;
	}
}
function mlEffectLoad(ob){
	var parent = ob.parentNode;
	while(parent.parentNode && parent.className.indexOf('mlmenu') == -1){
		parent = parent.parentNode;
	}
	if(parent.className.indexOf('fade') != -1){
		ob.style.display = 'none';
		ob.className = 'fade';
		mlSetOpacity(ob,0);
	}
	else if(parent.className.indexOf('shake') != -1){
		ob.className = 'shake';
	}
	else if(parent.className.indexOf('blindv') != -1){
		ob.className = 'blindv';
	}
	else if(parent.className.indexOf('blindh') != -1){
		ob.className = 'blindh';
	}
	else if(parent.className.indexOf('box') != -1){
		ob.className = 'box';
	}
	ob.style.display = 'none';
}
function ancestor(child, parent){
	try{
		if(child==null)return false;//Saves checking elsewhere
		for(; child.parentNode; child = child.parentNode){
				if(child.parentNode === parent) return true;
			}
		return false;
	}
	catch(error){
		//Mozilla bug, it  must be a text control which can not be in the menu so we will foolishly assume false
		return false;
	}
}
function byClass(parent,c,tag){
	var all = parent.getElementsByTagName(tag);
	var returna = new Array();
	for(var i=0;i<all.length;i++){
		if(all[i].className.indexOf(c) != -1){
			returna[returna.length] = all[i]
		}
	}
	return returna;
}
function findLast(ob){
	if(ob.lastChild.nodeType == 1){
		return ob.lastChild
	}
	return ob.lastChild.previousSibling;
}


