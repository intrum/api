(() => {
	'use strict';

	var http_build_query = (_request , dpth , ENCODE_KEYS , ENCODE_VALS) => {
		var r = [];
		if(!dpth){
			dpth = '';
		}
		if(typeof(_request) == 'object'){
			for(var k in _request){
				var key = ((dpth) ? '[' + k + ']' : k);
				if(_request.hasOwnProperty(k)){
					if(typeof(_request[k]) == 'object'){
						r.push(Intrum.Browser.http_build_query(_request[k] , dpth + key , ENCODE_KEYS , ENCODE_VALS));
					}else{
						r.push(((ENCODE_KEYS === false) ? dpth + key : encodeURIComponent(dpth + key)) + '=' + ((ENCODE_VALS === false) ? _request[k] : encodeURIComponent(_request[k])));
					}
				}
			}
		}
		return r.join('&');
	}
	var addClass = (el , cl) => {
		var classes = el.className.split(" ");
		if (classes.indexOf(cl) === -1) {
			classes.push(cl);
		}
		el.className = classes.join(" ");
	}
	var removeClass = (el , cl) => {
		el.className = el.className.split(" ").filter((s) => {return cl != s;}).join(" ");
	}
	var prepareMessageText = (text) => {
		var r = {};
		var d = document.createElement('div');
		d.innerHTML = text;
		var se;
		if (se = d.querySelector('.SenderEmployee')) {
			r.sender = se.textContent || se.innerText || "";
			se.parentElement.removeChild(se);
		}
		r.text = d.textContent || d.innerText || "";
		return r;
	}
	var Ajax = {
		get : function (url) {
			return new Promise((resolove , reject) => {
				var xhr = new XMLHttpRequest();
				xhr.open('GET' , url);
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if (xhr.status != 200) {
							var Err = new Error(xhr.status + ': ' + xhr.responseText);
							console.log(Err);
							reject();
						} else {
							resolove(xhr.responseText);
						}
					}
				}
				xhr.send();
			});
		} ,
		post : function (url , request) {
			return new Promise((resolove , reject) => {
				var xhr = new XMLHttpRequest();
				xhr.open('POST' , url);
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.onreadystatechange = () => {
					if (xhr.readyState == 4) {
						if (xhr.status != 200) {
							var Err = new Error(xhr.status + ': ' + xhr.responseText);
							console.log(Err);
							reject();
						} else {
							resolove(xhr.responseText);
						}
					}
				};
				xhr.send(http_build_query(request));
			});
		}
	};

	window.CRMDialog = new function(){
		var self = this;

		var dialogid = null;
		var client = {
			key : null ,
			id  : null
		};
		var conteiner = null;
		var intrum_host = null;

		var WSSocket = null;
		var packtext = '';

		var Now = new Date();

		var getClient = () => {
			return new Promise((resolove , reject) => {
				var ck = localStorage.getItem('crmdialog.client');
				if (!ck) {
					Ajax.get('/server_side.php?action=getClient&dialogid=' + dialogid).then((ck) => {
						if (ck) {
							localStorage.setItem('crmdialog.client' , ck);
							resolove(ck);
						}
					} , reject);
				} else {
					resolove(ck);
				}
			});
		}

		var parsePack = (inpack) => {
			inpack = packtext + inpack;
			var messageData = inpack.split('}#end');
			var ret = [];
			if(messageData[messageData.length-1]=='' || messageData[messageData.length-1]=='\n'){
				for(var i=0;i<messageData.length-1;i++){
					messageData[i] = messageData[i].replace('start#{' , '').trim();
					messageData[i] = messageData[i].split(',');
					var data = {};
					var command = '';
					for(var j=0;j<messageData[i].length;j++){
						if(messageData[i][j]){
							messageData[i][j] = messageData[i][j].split(':');
							if(messageData[i][j][0] == 'action'){
								var command = messageData[i][j][1];
							}else{
								data[messageData[i][j][0]] = messageData[i][j][1];
							}
						}
					}
					ret.push({command : command , fields : data});
				}
				packtext = '';
			}else{
				packtext += inpack;
				return false;
			}
			return ret;
		}

		var buildPack = (action , fields) => {
			var returnStr = 'start#{action:' + action + ',';
			var keyVals = [];
			for(var key in fields)
			{
				keyVals[keyVals.length] = key + ':' + fields[key];
			}
			returnStr += keyVals.join(',');
			returnStr += '}#end';
			return returnStr;
		}

		var send = (raw) => {
			if (WSSocket !== null) {
				WSSocket.send(raw);
			}
		}

		var auth = () => {
			send(buildPack('login' , {
				'key' : client.key
			}));
		}

		var askOnline = () => {
			send(buildPack('onlinelist' , {
				serverreceiverid   : intrum_host ,
				serverreceivertype : 'intrum' ,
				customer           : client.id ,
				group              : dialogid
			}));
		}

		var ondata = (raw) => {
			var ms = parsePack(raw);
			var ln = ms.length
			if (ln) {
				for (var i = 0;i < ln;i++) {
					switch (ms[i].command) {
						case 'ping':
							send('start#{action:pong}#end');
						break;
						case 'login':
							var el = conteiner.querySelector(".Online");
							removeClass(el , 'connecting');
							if (ms[i].fields.status == 'success') {
								initHistory();
								askOnline();
							} else {
								var Err = new Error("Auth error");
								console.error(Err);
							}
						break;
						case 'delivery':
							checkMessage(ms[i].fields.hash , 'sending');
						break;
						case 'managerread':
							checkMessage(ms[i].fields.hash , 'noread');
						break;
						case 'onlinelist':
							var el = conteiner.querySelector(".Online");
							if (ms[i].fields.list.indexOf("on") !== -1) {
								addClass(el , 'on');
							} else {
								removeClass(el , 'on');
							}
						break;
						case 'message':
							console.log(ms[i].fields);
							var m = {
								type   : "in" ,
								date   : new Date(parseInt(ms[i].fields.date)) ,
								status : false ,
								text   : decodeURIComponent(ms[i].fields.message) ,
								ishist : false ,
								hash   : ms[i].fields.hash

							};
							AddMessage(m , true);
						break;
						default:
							console.warn("Unknown command server:" + ms[i].command);
						break;
					}
				}
			}
		}

		var ConnectToServer = function () {
			var url = "wss://auth.intrumnet.com:6502";

			WSSocket = new WebSocket(url);


			WSSocket.onopen = function(evt){
				auth();
			}
			WSSocket.onclose = function(evt){
				WSSocket = null;
			}
			WSSocket.onmessage = function (evt) {
				ondata(evt.data);
			}
			WSSocket.onerror = function (evt) {
				WSSocket = null;
			}

		}

		var loadHistory = function (page , count) {
			return new Promise((resolove , reject) => {
				var request = {
					group  : dialogid ,
					client : client.id ,
					date   : Math.ceil(Now.getTime() / 1000) ,
					page   : page ,
					count  : count
				}
				Ajax.post('server_side.php?action=loadHistory' , request).then((response) => {
					if (response) {
						resolove(JSON.parse(response));
					}
				});
			});
		}

		var getUniqueMessageID = function () {
			var l = 32;
			var s = '';
			var str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-_';
			for (var i = 0;i < l;i++) {
				s += str.charAt(Math.floor(Math.random() * 64));
			}
			return s;
		}

		var SendMessage = (text) => {
			if (text.trim()){
				var data = {
					hash               : getUniqueMessageID() ,
					message            : encodeURIComponent(text) ,
					serverreceivertype : 'intrum' ,
					serverreceiverid   : intrum_host ,
					group              : dialogid ,
					customer           : client.id
				}
				var m = {
					hash   : data.hash ,
					date   : new Date() ,
					text   : text ,
					type   : "out" ,
					status : false ,
					ishist : false ,
					isSending : true
				}
				AddMessage(m , true);
				send(buildPack('message' , data));
			} else {
				alert('Пустое сообщение');
			}
		}

		var checkMessage = (hash , clear) => {
			var historyConteiner = conteiner.querySelector('.HistoryWrapper ul.History');
			var mel = historyConteiner.querySelector('li[data-hash="' + hash + '"]');
			if (mel) {
				var classes = mel.className.split(" ");
				mel.className = classes.filter((s) => {return s != clear}).join(" ");
			}
		}

		var AddMessage = (m , scroll) => {
			var historyConteiner = conteiner.querySelector('.HistoryWrapper ul.History');
			var mel = document.createElement('li');
			var MD = prepareMessageText(m.text);
			mel.innerHTML = '<span>\
				' + ((m.type == 'in') ? '<span class="manager_icon"></span>' : '<span class="client_icon"></span>') + '\
			</span><span>\
				<div class="head"><span class="iconWrap"><span class="message-icon"></span></span><span class="name">' + (('sender' in MD) ? MD.sender : ((m.type == 'in') ? "Сотрудник" : "Я")) + '</span><span class="date">' + m.date.toLocaleString() + '</span></div>\
				<div class="text">' + MD.text + '</div>\
			</span>';
			mel.setAttribute('data-hash' , m.hash);
			var classes = [m.type];
			if (('isSending' in m) && m.isSending) {
				classes.push('sending');
			}
			if (!m.status){
				classes.push('noread');
			}
			if (classes.length) {
				mel.className = classes.join(" ");
			}
			if (!m.ishist) {
				historyConteiner.appendChild(mel);
			} else {
				historyConteiner.prependChild(mel)
			}
			if (scroll === true) {
				historyConteiner.scrollTop = (historyConteiner.scrollHeight - historyConteiner.offsetHeight);
			}
		}

		var initHistory = () => {
			var historyConteiner = conteiner.querySelector('.HistoryWrapper ul.History');
			var page = 0;
			var count = 20;

			loadHistory(page , count).then((d) => {
				if (page == 0 && d.total > count) {

				}
				var ln = d.list.length;
				for (;ln--;) {
					var m = {
						text   : d.list[ln].text ,
						date   : new Date(parseInt(d.list[ln].date)) ,
						hash   : d.list[ln].hash ,
						type   : (d.list[ln].sender_type == 'client') ? 'out' : "in" ,
						status : d.list[ln].status ,
						ishist : (page > 0) ? true :false
					}
					AddMessage(m);
					// console.log(d.list[ln]);
				}
				if (page == 0) {
					historyConteiner.scrollTop = (historyConteiner.scrollHeight - historyConteiner.offsetHeight);
				}
				page++;
			});
		}

		var initConteiner = () => {
			return new Promise((resolove) => {
				Ajax.get('views/index.html').then((html) => {
					conteiner.innerHTML = html;
					var sendMessageButton = conteiner.querySelector('.SendMessage');
					var messageInput = conteiner.querySelector('textarea[name="message[text]"]');
					sendMessageButton.onclick = function () {
						SendMessage(messageInput.value);
						messageInput.value = "";
					}
					messageInput.onkeydown = (e) => {
						if (e.keyCode == 13) {
							e.stopPropagation();
							e.preventDefault();


							SendMessage(messageInput.value);
							messageInput.value = "";
							return;
						}
					}
					resolove();
				})
			});
		}

		this.init = (cont , did , ih) => {
			if (cont && did && ih) {
				conteiner = cont;
				dialogid = did;
				intrum_host = ih;
				initConteiner().then(() => {
					getClient().then((cl) => {
						var d = cl.split(":");
						client.key = d[1];
						client.id = d[0];
						ConnectToServer();
					} , () => {

					});
				});
			} else {
				var err = new ReferenceError("check conteiner or dialogid");
				console.error(err);
			}
		}
	}

})();