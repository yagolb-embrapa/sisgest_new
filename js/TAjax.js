// JavaScript Document
/*

Classe para manipulaao de ajax.
Inclui funoes crossbrowsing e  compativel com a maioria dos browsers atuais.

*/

/*
modo de usar a classe...

primeiro: numa tag <script> inclua esse arquivo...
segundo: numa outra tag <script> (sei lah pq num dah pra ser na mesma), crie uma variavel q seja "new TAjax();"...
terceiro: use o objeto e seja feliz.. .haha
*/

var TAjax = function(){

	this.loadingImg; //imagem que aparece qdo se faz loading...
	this.loadingPage = ""; //pagina que aparece no loading;
	
	var tempS;
	
	
function concatenaCB(campo,campoTxt){
	var listaMarcados = document.getElementsByName(campo);
	if (typeof campoTxt != "undefined")	var listaMarcadosTxt = document.getElementsByName(campoTxt);
	else campoTxt = false;	
	
	var c = '';	
		for (i = 0; i < listaMarcados.length; i++) {
		if (listaMarcados[i].checked) {
			if (c == ''){
				if (!campoTxt){
					c = listaMarcados[i].value;					
				}else{
					c = listaMarcados[i].value+'#'+listaMarcadosTxt[i].value;
				}
			}else{
				if (!campoTxt){
					c += '#'+listaMarcados[i].value;					
				}else{
					c += '#'+listaMarcados[i].value+'#'+listaMarcadosTxt[i].value;
				}
			}
		}
	}
	return c;
}
	
//================================================
//FUNO extraiScript - Para executar JavaScript dentro do AJAX
//================================================
	function extraiScript(texto){
//Maravilhosa funo feita pelo SkyWalker.TO do imasters/forum
//http://forum.imasters.com.br/index.php?showtopic=165277
// inicializa o inicio ><
    var ini = 0;
    // loop enquanto achar um script
    while (ini!=-1){
        // procura uma tag de script
        ini = texto.indexOf('<script', ini);
        // se encontrar
        if (ini >=0){
            // define o inicio para depois do fechamento dessa tag
            ini = texto.indexOf('>', ini) + 1;
            // procura o final do script
            var fim = texto.indexOf('</script>', ini);
            // extrai apenas o script
            codigo = texto.substring(ini,fim);
            // executa o script
            //eval(codigo);
            /**********************
            * Alterado por Micox - micoxjcg@yahoo.com.br
            * Alterei pois com o eval no executava funes.
            ***********************/
            novo = document.createElement("script")
            novo.text = codigo;
            document.body.appendChild(novo);
        }
    }
}	
	
	
//Funao que carrega a div com o conteudo retornado da pagina passada como parametro;
/*
Parametros:
	->div: A id da div a ser alterada
	->pagina: 
*/
	this.loadDiv = function(div,pagina){	
		var xmlhttp;
		//Se for IE, vamo usa o ActiveX mesmo...	
		if (window.ActiveXObject){
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			//Senaum, vai o objeto nativo do JavaScript
			xmlhttp = new XMLHttpRequest();
		}

		var divAlterada = document.getElementById(div);
		if (pagina == '') divAlterada.innerHTML = '';
		else{		
			xmlhttp.open('GET', pagina);
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState==4){
					var final = unescape(xmlhttp.responseText.replace(/\+/g," "));
					divAlterada.innerHTML = xmlhttp.responseText;
					extraiScript(final);				
				}
			};
			xmlhttp.send(null);
		}
	}		
	
	this.loadValue = function(obj,pagina){	
		var xmlhttp;
		//Se for IE, vamo usa o ActiveX mesmo...	
		if (window.ActiveXObject){
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			//Senaum, vai o objeto nativo do JavaScript
			xmlhttp = new XMLHttpRequest();
		}

		var objAlterado = document.getElementById(obj);
		if (pagina == '') objAlterado.value = '';
		else{		
			xmlhttp.open('GET', pagina);
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState==4){
					var final = unescape(xmlhttp.responseText.replace(/\+/g," "));
					objAlterado.value = xmlhttp.responseText;
					extraiScript(final);				
				}
			};
			xmlhttp.send(null);
		}
	}	
	
//post por ajax... 
// checks e donot nao definidos
	
	this.ajaxFormPost = function(div,pagina,pform,checks,donot){
		var xmlhttp;
		//Se for IE, vamo usa o ActiveX mesmo...	
		if (window.ActiveXObject){
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
		//Senaum, vai o objeto nativo do JavaScript
		xmlhttp = new XMLHttpRequest();
		}
		
		var frm = document.getElementById(pform);
		var divAlterada = document.getElementById(div);
		var params = "";
		//criando os parametros para serem passados por post... frescura do k***...
		for (var i = 0; i<frm.length; i++){
			if ((frm.elements[i].type!='button')&&(frm.elements[i].type!='submit')&&(frm.elements[i].type!='reset')&&(frm.elements[i].type!='checkbox')){	
				if (typeof donot == "undefined"){
										
					if (frm.elements[i].type=='radio'){
						if (frm.elements[i].checked){
							if (params!="") params += "&";
							params += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value);
						}
					}	
					else{
						if (params!="") params += "&";
						params += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value);
					}
					
					
				}
				else{
					if (frm.elements[i].type!=donot){
					if (i>0) params += "&";					
					params += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value);
					}
				}
				
			}
		}
		
		
		if (typeof checks != "undefined"){
			if (typeof checks == "string"){
				if (params != "") params += "&"
				params += checks+"="+encodeURIComponent(concatenaCB(checks));
			}
			else{
				for (var i=0; i<checks.length; i++){
					if (params!="") params+="&";
					params += checks[i]+"="+encodeURIComponent(concatenaCB(checks[i]));
				}
			}
		}
		
		xmlhttp.open('POST', pagina, true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");
		if (!typeof loadingImg == 'undefined') divAlterada.innerHTML = "<img src='"+this.loadingImg+"' />";
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState==4 && xmlhttp.status == 200){
				var final = unescape(xmlhttp.responseText.replace(/\+/g," "));
				divAlterada.innerHTML = xmlhttp.responseText;
				extraiScript(final);
			}
		};
		xmlhttp.send(params);
		
	}
	
	this.hideElement = function(div){
		var divAlterada = document.getElementById(div);
		divAlterada.style.visibility = "hidden";
		divAlterada.style.display = "none";
	}
	
	//mostra a div... com a opcao d pode escolhe qual o display =)
	this.showElement = function(div,disp){
		var divAlterada = document.getElementById(div);
		if (typeof disp == 'undefined') disp = 'inline'; // para o padrao d display... inline
		divAlterada.style.visibility = "visible";
		divAlterada.style.display = disp;
	}
	
	this.openById = function(idSt,dis){
		
		if (typeof dis == 'undefined') dis = 'block';
		var cl = 'close'+idSt;
		var op = 'open'+idSt;
		var cont = 'content'+idSt;
		this.showElement(cl);
		this.showElement(cont,dis);
		this.hideElement(op);
		
	}
	
	this.closeById = function(idSt){
		
		var cl = 'close'+idSt;
		var op = 'open'+idSt;
		var cont = 'content'+idSt;
		
		this.hideElement(cont);
		this.hideElement(cl);
		this.showElement(op);
	}
	
	this.tempShow = function(div,tempo,st){
		if (typeof tempo == "undefined") tempo = 5000;
		if (typeof st == "undefined") st = 'block';
		
			window.clearTimeout(tempS);
			this.showElement(div,st);
			tempS = window.setTimeout("forceHide('"+div+"');",tempo);
			
	}
	


}

function forceHide(div){
	var divAlterada = document.getElementById(div);
	divAlterada.style.visibility = "hidden";
	divAlterada.style.display = "none";
}

function checaData(sdata){
	ndata = sdata.split("/");
	if (ndata.length<3) return false;
	if (ndata[0]>31) return false;
	if (ndata[0]<1) return false;
	if ((ndata[1]==2)&&(ndata[0]>29)) return false;
	if ((ndata[1]>12)||(ndata[1]<1)) return false;
	if (ndata[2]<1900) return false;	
	return true;
}

function periodoValido(dtIni, dtFim){ //se true, a primeira menor q a segunda
	
	if (!checaData(dtIni)) return false;
	if (!checaData(dtFim)) return false;
	
	dti = dtIni.split("/").reverse();
	dt1 = new Date(dti[0], dti[1], dti[2]);
	
	dtf = dtFim.split("/").reverse();
	dt2 = new Date(dtf[0], dtf[1], dtf[2]);
	
	if (dt1 < dt2) return true;
	else return false;
	
	
}

function periodoValidoId(dtIni1, dtFim1){ //se true, a primeira menor q a segunda
	
	var dtIni = document.getElementById(dtIni1).value;
	var dtFim = document.getElementById(dtFim1).value;
	
	if (!checaData(dtIni)) return false;
	if (!checaData(dtFim)) return false;
	
	dti = dtIni.split("/").reverse();
	dt1 = new Date(dti[0], dti[1], dti[2]);
	
	dtf = dtFim.split("/").reverse();
	dt2 = new Date(dtf[0], dtf[1], dtf[2]);
	
	if (dt1 < dt2) return true;
	else return false;
	
	
}

//================================================
//MSCARAS COMUNS SOMENTE COM NUMEROS - onKeyPress="mascara(this,telefone);"
//AUTOR: Alexandre I. Kopelevitch
//================================================


function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function soNumeros(v){
	return 	v.replace(/\D/g,"")
}

function soLetras(v){
	v = v.replace(/\d/g,"")
    v = v.replace(/\W/g,"")
	v = v.toUpperCase();
	return v
}


function maskFone(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que no  dgito
    v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca parnteses em volta dos dois primeiros dgitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")    //Coloca hfen entre o quarto e o quinto dgitos
    return v
}

function maskCpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que no  dgito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dgitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dgitos
                                             //de novo (para o segundo bloco de nmeros)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hfen entre o terceiro e o quarto dgitos
    return v
}

function maskCnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que no  dgito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dgitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dgitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dgitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hfen depois do bloco de quatro dgitos
    return v
}

function maskCep(v){
    v=v.replace(/D/g,"")                //Remove tudo o que no  dgito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2") //Esse  to fcil que no merece explicaes
    return v
}

function maskDt(v){
    v=v.replace(/\D/g,"")                //Remove tudo o que no  dgito
    v=v.replace(/^(\d\d)(\d)/,"$1/$2")       //Coloca um ponto entre o terceiro e o quarto dgitos
    v=v.replace(/(\d\d)(\d)/,"$1/$2") //Esse  to fcil que no merece explicaes
    return v
}

// funcao concatena CB d novo...
function concatenaCB(campo,campoTxt){
	var listaMarcados = document.getElementsByName(campo);
	if (typeof campoTxt != "undefined")	var listaMarcadosTxt = document.getElementsByName(campoTxt);
	else campoTxt = false;	
	var c = '';	
		for (i = 0; i < listaMarcados.length; i++) {
		if (listaMarcados[i].checked) {
			if (c == ''){
				if (!campoTxt){
					c = listaMarcados[i].value;					
				}else{
					c = listaMarcados[i].value+'#'+listaMarcadosTxt[i].value;
				}
			}else{
				if (!campoTxt){
					c += '#'+listaMarcados[i].value;					
				}else{
					c += '#'+listaMarcados[i].value+'#'+listaMarcadosTxt[i].value;
				}
			}
		}
	}
	return c;
}

//concatenar valores que no sejam checkbox ou algo q tenha a propriedade ENABLED
function concatenaValores(campo){
	var listaMarcados = document.getElementsByName(campo);

	var c = '';	
		for (i = 0; i < listaMarcados.length; i++) {
			if (c == ''){
					c = listaMarcados[i].value;					
			}else{
					c += '#'+listaMarcados[i].value;					
			}
		}
	
	return c;
}






/***********************************************
* Fixed ToolTip script- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
		
var tipwidth='150px' //default tooltip width
var tipbgcolor='#E3DBC4'  //tooltip bgcolor
var disappeardelay=250  //tooltip disappear speed onMouseout (in miliseconds)
var vertical_offset="0px" //horizontal offset of tooltip from anchor link
var horizontal_offset="-3px" //horizontal offset of tooltip from anchor link

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="fixedtipdiv" align="center" style="visibility:hidden;display:inline;background-color:'+tipbgcolor+'" ></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, tipwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (tipwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
//dropmenuobj.widthobj.width=tipwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
}
return edgeoffset
}

function fixedtooltip(menucontents, obj, e, tipwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidetip()
dropmenuobj=document.getElementById? document.getElementById("fixedtipdiv") : fixedtipdiv
dropmenuobj.innerHTML=menucontents

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", tipwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}
}

function hidetip(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidetip(){
if (ie4||ns6)
delayhide=setTimeout("hidetip()",disappeardelay)
}

function clearhidetip(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}



