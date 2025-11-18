/*
 * jQuery JSON Plugin
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 * copyrighted 2005 by Bob Ippolito.
 */
(function(e){e.toJSON=function(a){if(typeof JSON=="object"&&JSON.stringify)return JSON.stringify(a);var b=typeof a;if(a===null)return"null";if(b!="undefined"){if(b=="number"||b=="boolean")return a+"";if(b=="string")return e.quoteString(a);if(b=="object"){if(typeof a.toJSON=="function")return e.toJSON(a.toJSON());if(a.constructor===Date){var c=a.getUTCMonth()+1;if(c<10)c="0"+c;var d=a.getUTCDate();if(d<10)d="0"+d;b=a.getUTCFullYear();var f=a.getUTCHours();if(f<10)f="0"+f;var g=a.getUTCMinutes();if(g<
10)g="0"+g;var h=a.getUTCSeconds();if(h<10)h="0"+h;a=a.getUTCMilliseconds();if(a<100)a="0"+a;if(a<10)a="0"+a;return'"'+b+"-"+c+"-"+d+"T"+f+":"+g+":"+h+"."+a+'Z"'}if(a.constructor===Array){c=[];for(d=0;d<a.length;d++)c.push(e.toJSON(a[d])||"null");return"["+c.join(",")+"]"}c=[];for(d in a){b=typeof d;if(b=="number")b='"'+d+'"';else if(b=="string")b=e.quoteString(d);else continue;if(typeof a[d]!="function"){f=e.toJSON(a[d]);c.push(b+":"+f)}}return"{"+c.join(", ")+"}"}}};e.evalJSON=function(a){if(typeof JSON==
"object"&&JSON.parse)return JSON.parse(a);return eval("("+a+")")};e.secureEvalJSON=function(a){if(typeof JSON=="object"&&JSON.parse)return JSON.parse(a);var b=a;b=b.replace(/\\["\\\/bfnrtu]/g,"@");b=b.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]");b=b.replace(/(?:^|:|,)(?:\s*\[)+/g,"");if(/^[\],:{}\s]*$/.test(b))return eval("("+a+")");else throw new SyntaxError("Error parsing JSON, source is not valid.");};e.quoteString=function(a){if(a.match(i))return'"'+a.replace(i,
function(b){var c=j[b];if(typeof c==="string")return c;c=b.charCodeAt();return"\\u00"+Math.floor(c/16).toString(16)+(c%16).toString(16)})+'"';return'"'+a+'"'};var i=/["\\\x00-\x1f\x7f-\x9f]/g,j={"\u0008":"\\b","\t":"\\t","\n":"\\n","\u000c":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"}})(jQuery);
/* Metadata - Dual licensed under the MIT and GPL licenses */
(function(e){e.extend({metadata:{defaults:{type:"class",name:"metadata",cre:/({.*})/,single:"metadata"},setType:function(b,a){this.defaults.type=b;this.defaults.name=a},get:function(b,a){a=e.extend({},this.defaults,a);if(!a.single.length)a.single="metadata";var c=e.data(b,a.single);if(c)return c;c="{}";var h=function(d){if(typeof d!="string")return d;return d=eval("("+d+")")};if(a.type=="html5"){var g={};e(b.attributes).each(function(){var d=this.nodeName;if(d.match(/^data-/))d=d.replace(/^data-/,
"");else return true;g[d]=h(this.nodeValue)})}else{if(a.type=="class"){var f=a.cre.exec(b.className);if(f)c=f[1]}else if(a.type=="elem"){if(!b.getElementsByTagName)return;f=b.getElementsByTagName(a.name);if(f.length)c=e.trim(f[0].innerHTML)}else if(b.getAttribute!=undefined)if(f=b.getAttribute(a.name))c=f;g=h(c.indexOf("{")<0?"{"+c+"}":c)}e.data(b,a.single,g);return g}}});e.fn.metadata=function(b){return e.metadata.get(this[0],b)}})(jQuery);
// ColorBox v1.3.19 - jQuery lightbox plugin
// (c) 2011 Jack Moore - jacklmoore.com
// License: http://www.opensource.org/licenses/mit-license.php
(function(a,b,c){function Z(c,d,e){var g=b.createElement(c);return d&&(g.id=f+d),e&&(g.style.cssText=e),a(g)}function $(a){var b=y.length,c=(Q+a)%b;return c<0?b+c:c}function _(a,b){return Math.round((/%/.test(a)?(b==="x"?z.width():z.height())/100:1)*parseInt(a,10))}function ba(a){return K.photo||/\.(gif|png|jpe?g|bmp|ico)((#|\?).*)?$/i.test(a)}function bb(){var b;K=a.extend({},a.data(P,e));for(b in K)a.isFunction(K[b])&&b.slice(0,2)!=="on"&&(K[b]=K[b].call(P));K.rel=K.rel||P.rel||"nofollow",K.href=K.href||a(P).attr("href"),K.title=K.title||P.title,typeof K.href=="string"&&(K.href=a.trim(K.href))}function bc(b,c){a.event.trigger(b),c&&c.call(P)}function bd(){var a,b=f+"Slideshow_",c="click."+f,d,e,g;K.slideshow&&y[1]?(d=function(){F.text(K.slideshowStop).unbind(c).bind(j,function(){if(K.loop||y[Q+1])a=setTimeout(W.next,K.slideshowSpeed)}).bind(i,function(){clearTimeout(a)}).one(c+" "+k,e),r.removeClass(b+"off").addClass(b+"on"),a=setTimeout(W.next,K.slideshowSpeed)},e=function(){clearTimeout(a),F.text(K.slideshowStart).unbind([j,i,k,c].join(" ")).one(c,function(){W.next(),d()}),r.removeClass(b+"on").addClass(b+"off")},K.slideshowAuto?d():e()):r.removeClass(b+"off "+b+"on")}function be(b){U||(P=b,bb(),y=a(P),Q=0,K.rel!=="nofollow"&&(y=a("."+g).filter(function(){var b=a.data(this,e).rel||this.rel;return b===K.rel}),Q=y.index(P),Q===-1&&(y=y.add(P),Q=y.length-1)),S||(S=T=!0,r.show(),K.returnFocus&&a(P).blur().one(l,function(){a(this).focus()}),q.css({opacity:+K.opacity,cursor:K.overlayClose?"pointer":"auto"}).show(),K.w=_(K.initialWidth,"x"),K.h=_(K.initialHeight,"y"),W.position(),o&&z.bind("resize."+p+" scroll."+p,function(){q.css({width:z.width(),height:z.height(),top:z.scrollTop(),left:z.scrollLeft()})}).trigger("resize."+p),bc(h,K.onOpen),J.add(D).hide(),I.html(K.close).show()),W.load(!0))}function bf(){!r&&b.body&&(Y=!1,z=a(c),r=Z(X).attr({id:e,"class":n?f+(o?"IE6":"IE"):""}).hide(),q=Z(X,"Overlay",o?"position:absolute":"").hide(),s=Z(X,"Wrapper"),t=Z(X,"Content").append(A=Z(X,"LoadedContent","width:0; height:0; overflow:hidden"),C=Z(X,"LoadingOverlay").add(Z(X,"LoadingGraphic")),D=Z(X,"Title"),E=Z(X,"Current"),G=Z(X,"Next"),H=Z(X,"Previous"),F=Z(X,"Slideshow").bind(h,bd),I=Z(X,"Close")),s.append(Z(X).append(Z(X,"TopLeft"),u=Z(X,"TopCenter"),Z(X,"TopRight")),Z(X,!1,"clear:left").append(v=Z(X,"MiddleLeft"),t,w=Z(X,"MiddleRight")),Z(X,!1,"clear:left").append(Z(X,"BottomLeft"),x=Z(X,"BottomCenter"),Z(X,"BottomRight"))).find("div div").css({"float":"left"}),B=Z(X,!1,"position:absolute; width:9999px; visibility:hidden; display:none"),J=G.add(H).add(E).add(F),a(b.body).append(q,r.append(s,B)))}function bg(){return r?(Y||(Y=!0,L=u.height()+x.height()+t.outerHeight(!0)-t.height(),M=v.width()+w.width()+t.outerWidth(!0)-t.width(),N=A.outerHeight(!0),O=A.outerWidth(!0),r.css({"padding-bottom":L,"padding-right":M}),G.click(function(){W.next()}),H.click(function(){W.prev()}),I.click(function(){W.close()}),q.click(function(){K.overlayClose&&W.close()}),a(b).bind("keydown."+f,function(a){var b=a.keyCode;S&&K.escKey&&b===27&&(a.preventDefault(),W.close()),S&&K.arrowKey&&y[1]&&(b===37?(a.preventDefault(),H.click()):b===39&&(a.preventDefault(),G.click()))}),a("."+g,b).live("click",function(a){a.which>1||a.shiftKey||a.altKey||a.metaKey||(a.preventDefault(),be(this))})),!0):!1}var d={transition:"none",speed:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,opacity:.9,preloading:!0,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",open:!1,returnFocus:!0,reposition:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:undefined},e="colorbox",f="cbox",g=f+"Element",h=f+"_open",i=f+"_load",j=f+"_complete",k=f+"_cleanup",l=f+"_closed",m=f+"_purge",n=!a.support.opacity&&!a.support.style,o=n&&!c.XMLHttpRequest,p=f+"_IE6",q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X="div",Y;if(a.colorbox)return;a(bf),W=a.fn[e]=a[e]=function(b,c){var f=this;b=b||{},bf();if(bg()){if(!f[0]){if(f.selector)return f;f=a("<a/>"),b.open=!0}c&&(b.onComplete=c),f.each(function(){a.data(this,e,a.extend({},a.data(this,e)||d,b))}).addClass(g),(a.isFunction(b.open)&&b.open.call(f)||b.open)&&be(f[0])}return f},W.position=function(a,b){function i(a){u[0].style.width=x[0].style.width=t[0].style.width=a.style.width,t[0].style.height=v[0].style.height=w[0].style.height=a.style.height}var c=0,d=0,e=r.offset(),g=z.scrollTop(),h=z.scrollLeft();z.unbind("resize."+f),r.css({top:-9e4,left:-9e4}),K.fixed&&!o?(e.top-=g,e.left-=h,r.css({position:"fixed"})):(c=g,d=h,r.css({position:"absolute"})),K.right!==!1?d+=Math.max(z.width()-K.w-O-M-_(K.right,"x"),0):K.left!==!1?d+=_(K.left,"x"):d+=Math.round(Math.max(z.width()-K.w-O-M,0)/2),K.bottom!==!1?c+=Math.max(z.height()-K.h-N-L-_(K.bottom,"y"),0):K.top!==!1?c+=_(K.top,"y"):c+=Math.round(Math.max(z.height()-K.h-N-L,0)/2),r.css({top:e.top,left:e.left}),a=r.width()===K.w+O&&r.height()===K.h+N?0:a||0,s[0].style.width=s[0].style.height="9999px",r.dequeue().animate({width:K.w+O,height:K.h+N,top:c,left:d},{duration:a,complete:function(){i(this),T=!1,s[0].style.width=K.w+O+M+"px",s[0].style.height=K.h+N+L+"px",K.reposition&&setTimeout(function(){z.bind("resize."+f,W.position)},1),b&&b()},step:function(){i(this)}})},W.resize=function(a){S&&(a=a||{},a.width&&(K.w=_(a.width,"x")-O-M),a.innerWidth&&(K.w=_(a.innerWidth,"x")),A.css({width:K.w}),a.height&&(K.h=_(a.height,"y")-N-L),a.innerHeight&&(K.h=_(a.innerHeight,"y")),!a.innerHeight&&!a.height&&(A.css({height:"auto"}),K.h=A.height()),A.css({height:K.h}),W.position(K.transition==="none"?0:K.speed))},W.prep=function(b){function g(){return K.w=K.w||A.width(),K.w=K.mw&&K.mw<K.w?K.mw:K.w,K.w}function h(){return K.h=K.h||A.height(),K.h=K.mh&&K.mh<K.h?K.mh:K.h,K.h}if(!S)return;var c,d=K.transition==="none"?0:K.speed;A.remove(),A=Z(X,"LoadedContent").append(b),A.hide().appendTo(B.show()).css({width:g(),overflow:K.scrolling?"auto":"hidden"}).css({height:h()}).prependTo(t),B.hide(),a(R).css({"float":"none"}),o&&a("select").not(r.find("select")).filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one(k,function(){this.style.visibility="inherit"}),c=function(){function q(){n&&r[0].style.removeAttribute("filter")}var b,c,g=y.length,h,i="frameBorder",k="allowTransparency",l,o,p;if(!S)return;l=function(){clearTimeout(V),C.hide(),bc(j,K.onComplete)},n&&R&&A.fadeIn(100),D.html(K.title).add(A).show();if(g>1){typeof K.current=="string"&&E.html(K.current.replace("{current}",Q+1).replace("{total}",g)).show(),G[K.loop||Q<g-1?"show":"hide"]().html(K.next),H[K.loop||Q?"show":"hide"]().html(K.previous),K.slideshow&&F.show();if(K.preloading){b=[$(-1),$(1)];while(c=y[b.pop()])o=a.data(c,e).href||c.href,a.isFunction(o)&&(o=o.call(c)),ba(o)&&(p=new Image,p.src=o)}}else J.hide();K.iframe?(h=Z("iframe")[0],i in h&&(h[i]=0),k in h&&(h[k]="true"),h.name=f+ +(new Date),K.fastIframe?l():a(h).one("load",l),h.src=K.href,K.scrolling||(h.scrolling="no"),a(h).addClass(f+"Iframe").appendTo(A).one(m,function(){h.src="//about:blank"})):l(),K.transition==="fade"?r.fadeTo(d,1,q):q()},K.transition==="fade"?r.fadeTo(d,0,function(){W.position(0,c)}):W.position(d,c)},W.load=function(b){var c,d,e=W.prep;T=!0,R=!1,P=y[Q],b||bb(),bc(m),bc(i,K.onLoad),K.h=K.height?_(K.height,"y")-N-L:K.innerHeight&&_(K.innerHeight,"y"),K.w=K.width?_(K.width,"x")-O-M:K.innerWidth&&_(K.innerWidth,"x"),K.mw=K.w,K.mh=K.h,K.maxWidth&&(K.mw=_(K.maxWidth,"x")-O-M,K.mw=K.w&&K.w<K.mw?K.w:K.mw),K.maxHeight&&(K.mh=_(K.maxHeight,"y")-N-L,K.mh=K.h&&K.h<K.mh?K.h:K.mh),c=K.href,V=setTimeout(function(){C.show()},100),K.inline?(Z(X).hide().insertBefore(a(c)[0]).one(m,function(){a(this).replaceWith(A.children())}),e(a(c))):K.iframe?e(" "):K.html?e(K.html):ba(c)?(a(R=new Image).addClass(f+"Photo").error(function(){K.title=!1,e(Z(X,"Error").text("This image could not be loaded"))}).load(function(){var a;R.onload=null,K.scalePhotos&&(d=function(){R.height-=R.height*a,R.width-=R.width*a},K.mw&&R.width>K.mw&&(a=(R.width-K.mw)/R.width,d()),K.mh&&R.height>K.mh&&(a=(R.height-K.mh)/R.height,d())),K.h&&(R.style.marginTop=Math.max(K.h-R.height,0)/2+"px"),y[1]&&(K.loop||y[Q+1])&&(R.style.cursor="pointer",R.onclick=function(){W.next()}),n&&(R.style.msInterpolationMode="bicubic"),setTimeout(function(){e(R)},1)}),setTimeout(function(){R.src=c},1)):c&&B.load(c,K.data,function(b,c,d){e(c==="error"?Z(X,"Error").text("Request unsuccessful: "+d.statusText):a(this).contents())})},W.next=function(){!T&&y[1]&&(K.loop||y[Q+1])&&(Q=$(1),W.load())},W.prev=function(){!T&&y[1]&&(K.loop||Q)&&(Q=$(-1),W.load())},W.close=function(){S&&!U&&(U=!0,S=!1,bc(k,K.onCleanup),z.unbind("."+f+" ."+p),q.fadeTo(200,0),r.stop().fadeTo(300,0,function(){r.add(q).css({opacity:1,cursor:"auto"}).hide(),bc(m),A.remove(),setTimeout(function(){U=!1,bc(l,K.onClosed)},1)}))},W.remove=function(){a([]).add(r).add(q).remove(),r=null,a("."+g).removeData(e).removeClass(g).die()},W.element=function(){return a(P)},W.settings=d})(jQuery,document,this);
/* Valid8 */
(function($){$.fn.extend({valid8:function(b){return this.each(function(){$(this).data('valid',false);var a={regularExpressions:[],ajaxRequests:[],jsFunctions:[],validationEvents:['keyup','blur'],validationFrequency:1000,values:null,defaultErrorMessage:'Required'};if(typeof b=='string')a.defaultErrorMessage=b;if(this.type=='checkbox'){a.regularExpressions=[{expression:/^true$/,errormessage:a.defaultErrorMessage}];a.validationEvents=['click']}else a.regularExpressions=[{expression:/^.+$/,errormessage:a.defaultErrorMessage}];$(this).data('settings',$.extend(a,b));initialize(this)})},isValid:function(){var a=true;this.each(function(){validate(this);if($(this).data('valid')==false)a=false});return a}});function initializeDataObject(a){$(a).data('loadings',new Array());$(a).data('errors',new Array());$(a).data('valids',new Array());$(a).data('keypressTimer',null)}function initialize(a){initializeDataObject(a);activate(a)};function activate(b){var c=$(b).data('settings').validationEvents;if(typeof c=='string')$(b)[c](function(e){handleEvent(e,b)});else{$.each(c,function(i,a){$(b)[a](function(e){handleEvent(e,b)})})}};function validate(a){initializeDataObject(a);var b;if(a.type=='checkbox')b=a.checked.toString();else b=a.value;regexpValidation(b.replace(/^[ \t]+|[ \t]+$/,''),a)};function regexpValidation(b,c){$.each($(c).data('settings').regularExpressions,function(i,a){if(!a.expression.test(b))$(c).data('errors')[$(c).data('errors').length]=a.errormessage;else if(a.validmessage)$(c).data('valids')[$(c).data('valids').length]=a.validmessage});if($(c).data('errors').length>0)onEvent(c,'error',false);else if($(c).data('settings').jsFunctions.length>0){functionValidation(b,c)}else if($(c).data('settings').ajaxRequests.length>0){fileValidation(b,c)}else{onEvent(c,'valid',true)}};function functionValidation(c,d){$.each($(d).data('settings').jsFunctions,function(i,a){var v;if(a.values){if(typeof a.values=='function')v=a.values()}var b=v||c;handleLoading(d,a);if(a['function'](b).valid)$(d).data('valids')[$(d).data('valids').length]=a['function'](b).message;else $(d).data('errors')[$(d).data('errors').length]=a['function'](b).message});if($(d).data('errors').length>0)onEvent(d,'error',false);else if($(d).data('settings').ajaxRequests.length>0){fileValidation(c,d)}else{onEvent(d,'valid',true)}};function fileValidation(e,f){$.each($(f).data('settings').ajaxRequests,function(i,c){var v;if(c.values){if(typeof c.values=='function')v=c.values()}var d=v||{value:e};handleLoading(f,c);$.post(c.url,d,function(a,b){if(a.valid){$(f).data('valids')[$(f).data('valids').length]=a.message||c.validmessage||""}else{$(f).data('errors')[$(f).data('errors').length]=a.message||c.errormessage||""}if($(f).data('errors').length>0)onEvent(f,'error',false);else{onEvent(f,'valid',true)}},"json")})};function handleEvent(e,a){if(e.keyCode&&$(a).attr('value').length>0){clearTimeout($(a).data('keypressTimer'));$(a).data('keypressTimer',setTimeout(function(){validate(a)},$(a).data('settings').validationFrequency))}else if(e.keyCode&&$(a).attr('value').length<=0)return false;else{validate(a)}};function handleLoading(a,b){if(b.loadingmessage){$(a).data('loadings')[$(a).data('loadings').length]=b.loadingmessage;onEvent(a,'loading',false)}};function onEvent(a,b,c){var d=b.substring(0,1).toUpperCase()+b.substring(1,b.length),messages=$(a).data(b+'s');$(a).data(b,c);setStatus(a,b);setParentClass(a,b);setMessage(messages,a);$(a).trigger(b,[messages,a,b])}function setParentClass(a,b){var c=$(a).parent();c[0].className=(c[0].className.replace(/(^\s|(\s*(loading|error|valid)))/g,'')+' '+b).replace(/^\s/,'')}function setMessage(a,b){var c=$(b).parent();var d=b.id+"ValidationMessage";var e='validationMessage';if(!$('#'+d).length>0){c.append('<span id="'+d+'" class="'+e+'"></span>')}$('#'+d).html("");$('#'+d).text(a[0])};function setStatus(a,b){if(b=='valid'){$(a).data('valid',true)}else if(b=='error'){$(a).data('valid',false)}}})(jQuery);
/*
 * jQuery Color Animations
 * Copyright 2007 John Resig
 * Released under the MIT and GPL licenses.
 */
(function(d){function e(c){var a;if(c&&c.constructor==Array&&c.length==3)return c;if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(c))return[parseInt(a[1]),parseInt(a[2]),parseInt(a[3])];if(a=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(c))return[parseFloat(a[1])*2.55,parseFloat(a[2])*2.55,parseFloat(a[3])*2.55];if(a=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(c))return[parseInt(a[1],16),parseInt(a[2],
16),parseInt(a[3],16)];if(a=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(c))return[parseInt(a[1]+a[1],16),parseInt(a[2]+a[2],16),parseInt(a[3]+a[3],16)];return f[d.trim(c).toLowerCase()]}function g(c,a){var b;do{b=d.curCSS(c,a);if(b!=""&&b!="transparent"||d.nodeName(c,"body"))break;a="backgroundColor"}while(c=c.parentNode);return e(b)}d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(c,a){d.fx.step[a]=function(b){if(b.state==
0){b.start=g(b.elem,a);b.end=e(b.end)}b.elem.style[a]="rgb("+[Math.max(Math.min(parseInt(b.pos*(b.end[0]-b.start[0])+b.start[0]),255),0),Math.max(Math.min(parseInt(b.pos*(b.end[1]-b.start[1])+b.start[1]),255),0),Math.max(Math.min(parseInt(b.pos*(b.end[2]-b.start[2])+b.start[2]),255),0)].join(",")+")"}});var f={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,
100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,
128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0]}})(jQuery);
/* tipsy */
(function(b){function j(a){if(a.attr("title")||typeof a.attr("original-title")!="string")a.attr("original-title",a.attr("title")||"").removeAttr("title")}function k(a,c){this.$element=b(a);this.options=c;this.enabled=true;j(this.$element)}k.prototype={show:function(){var a=this.getTitle();if(a&&this.enabled){var c=this.tip();c.find(".tipsy-inner")[this.options.html?"html":"text"](a);c[0].className="tipsy";c.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).appendTo(document.body);a=
b.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight});var d=c[0].offsetWidth,h=c[0].offsetHeight,g=typeof this.options.gravity=="function"?this.options.gravity.call(this.$element[0]):this.options.gravity,f;switch(g.charAt(0)){case "n":f={top:a.top+a.height+this.options.offset,left:a.left+a.width/2-d/2};break;case "s":f={top:a.top-h-this.options.offset,left:a.left+a.width/2-d/2};break;case "e":f={top:a.top+a.height/2-h/2,left:a.left-d-this.options.offset};
break;case "w":f={top:a.top+a.height/2-h/2,left:a.left+a.width+this.options.offset};break}if(g.length==2)f.left=g.charAt(1)=="w"?a.left+a.width/2-15:a.left+a.width/2-d+15;c.css(f).addClass("tipsy-"+g);this.options.fade?c.stop().css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:this.options.opacity}):c.css({visibility:"visible",opacity:this.options.opacity})}},hide:function(){this.options.fade?this.tip().stop().fadeOut(function(){b(this).remove()}):this.tip().remove()},getTitle:function(){var a,
c=this.$element,d=this.options;j(c);d=this.options;if(typeof d.title=="string")a=c.attr(d.title=="title"?"original-title":d.title);else if(typeof d.title=="function")a=d.title.call(c[0]);return(a=(""+a).replace(/(^\s*|\s*$)/,""))||d.fallback},tip:function(){if(!this.$tip)this.$tip=b('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"/></div>');return this.$tip},validate:function(){this.$element[0].parentNode||this.hide()},enable:function(){this.enabled=true},
disable:function(){this.enabled=false},toggleEnabled:function(){this.enabled=!this.enabled}};b.fn.tipsy=function(a){function c(e){var i=b.data(e,"tipsy");if(!i){i=new k(e,b.fn.tipsy.elementOptions(e,a));b.data(e,"tipsy",i)}return i}function d(){var e=c(this);e.hoverState="in";a.delayIn==0?e.show():setTimeout(function(){e.hoverState=="in"&&e.show()},a.delayIn)}function h(){var e=c(this);e.hoverState="out";a.delayOut==0?e.hide():setTimeout(function(){e.hoverState=="out"&&e.hide()},a.delayOut)}if(a===
true)return this.data("tipsy");else if(typeof a=="string")return this.data("tipsy")[a]();a=b.extend({},b.fn.tipsy.defaults,a);a.live||this.each(function(){c(this)});if(a.trigger!="manual"){var g=a.live?"live":"bind",f=a.trigger=="hover"?"mouseleave":"blur";this[g](a.trigger=="hover"?"mouseenter":"focus",d)[g](f,h)}return this};b.fn.tipsy.defaults={delayIn:0,delayOut:0,fade:false,fallback:"",gravity:"n",html:false,live:false,offset:0,opacity:0.8,title:"title",trigger:"hover"};b.fn.tipsy.elementOptions=
function(a,c){return b.metadata?b.extend({},c,b(a).metadata()):c};b.fn.tipsy.autoNS=function(){return b(this).offset().top>b(document).scrollTop()+b(window).height()/2?"s":"n"};b.fn.tipsy.autoWE=function(){return b(this).offset().left>b(document).scrollLeft()+b(window).width()/2?"e":"w"};b.fn.tipsy.autoSNiy=function(){return (b(this).offset().top-b(document).scrollTop())>50?"s":"n"}})(jQuery);

/* http://keith-wood.name/countdown.html
   Countdown for jQuery v1.5.11.
   Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
   Modified by david 2012-1-11
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */
(function($){function Countdown(){this.regional=[];this.regional['']={labels:['Years','Months','Weeks','Days','Hours','Minutes','Seconds'],labels1:['Year','Month','Week','Day','Hour','Minute','Second'],compactLabels:['y','m','w','d'],whichLabels:null,timeSeparator:':',isRTL:false};this._defaults={until:null,since:null,timezone:null,serverSync:null,format:'dHMS',layout:'',compact:false,significant:0,description:'',expiryUrl:'',expiryText:'',alwaysExpire:false,onExpiry:null,onTick:null,tickInterval:1};$.extend(this._defaults,this.regional['']);this._serverSyncs=[];function timerCallBack(a){var b=(a||new Date().getTime());if(b-d>=1000){$.countdown._updateTargets();d=b}c(timerCallBack)}var c=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||null;var d=0;if(!c){setInterval(function(){$.countdown._updateTargets()},980)}else{d=window.mozAnimationStartTime||new Date().getTime();c(timerCallBack)}}var w='countdown';var Y=0;var O=1;var W=2;var D=3;var H=4;var M=5;var S=6;$.extend(Countdown.prototype,{markerClassName:'hasCountdown',_timerTargets:[],setDefaults:function(a){this._resetExtraLabels(this._defaults,a);extendRemove(this._defaults,a||{})},UTCDate:function(a,b,c,e,f,g,h,i){if(typeof b=='object'&&b.constructor==Date){i=b.getMilliseconds();h=b.getSeconds();g=b.getMinutes();f=b.getHours();e=b.getDate();c=b.getMonth();b=b.getFullYear()}var d=new Date();d.setUTCFullYear(b);d.setUTCDate(1);d.setUTCMonth(c||0);d.setUTCDate(e||1);d.setUTCHours(f||0);d.setUTCMinutes((g||0)-(Math.abs(a)<30?a*60:a));d.setUTCSeconds(h||0);d.setUTCMilliseconds(i||0);return d},periodsToSeconds:function(a){return a[0]*31557600+a[1]*2629800+a[2]*604800+a[3]*86400+a[4]*3600+a[5]*60+a[6]},_settingsCountdown:function(a,b){if(!b){return $.countdown._defaults}var c=$.data(a,w);return(b=='all'?c.options:c.options[b])},_attachCountdown:function(a,b){var c=$(a);if(c.hasClass(this.markerClassName)){return}c.addClass(this.markerClassName);var d={options:$.extend({},b),_periods:[0,0,0,0,0,0,0]};$.data(a,w,d);this._changeCountdown(a)},_addTarget:function(a){if(!this._hasTarget(a)){this._timerTargets.push(a)}},_hasTarget:function(a){return($.inArray(a,this._timerTargets)>-1)},_removeTarget:function(b){this._timerTargets=$.map(this._timerTargets,function(a){return(a==b?null:a)})},_updateTargets:function(){for(var i=this._timerTargets.length-1;i>=0;i--){this._updateCountdown(this._timerTargets[i])}},_updateCountdown:function(a,b){var c=$(a);b=b||$.data(a,w);if(!b){return}c.html(this._generateHTML(b));c[(this._get(b,'isRTL')?'add':'remove')+'Class']('countdown_rtl');var d=this._get(b,'onTick');if(d){var e=b._hold!='lap'?b._periods:this._calculatePeriods(b,b._show,this._get(b,'significant'),new Date());var f=this._get(b,'tickInterval');if(f==1||this.periodsToSeconds(e)%f==0){d.apply(a,[e])}}var g=b._hold!='pause'&&(b._since?b._now.getTime()<b._since.getTime():b._now.getTime()>=b._until.getTime());if(g&&!b._expiring){b._expiring=true;if(this._hasTarget(a)||this._get(b,'alwaysExpire')){this._removeTarget(a);var h=this._get(b,'onExpiry');if(h){h.apply(a,[])}var i=this._get(b,'expiryText');if(i){var j=this._get(b,'layout');b.options.layout=i;this._updateCountdown(a,b);b.options.layout=j}var k=this._get(b,'expiryUrl');if(k){window.location=k}}b._expiring=false}else if(b._hold=='pause'){this._removeTarget(a)}$.data(a,w,b)},_changeCountdown:function(a,b,c){b=b||{};if(typeof b=='string'){var d=b;b={};b[d]=c}var e=$.data(a,w);if(e){this._resetExtraLabels(e.options,b);extendRemove(e.options,b);this._adjustSettings(a,e);$.data(a,w,e);var f=new Date();if((e._since&&e._since<f)||(e._until&&e._until>f)){this._addTarget(a)}this._updateCountdown(a,e)}},_resetExtraLabels:function(a,b){var c=false;for(var n in b){if(n!='whichLabels'&&n.match(/[Ll]abels/)){c=true;break}}if(c){for(var n in a){if(n.match(/[Ll]abels[0-9]/)){a[n]=null}}}},_adjustSettings:function(a,b){var c;var d=this._get(b,'serverSync');var e=0;var f=null;for(var i=0;i<this._serverSyncs.length;i++){if(this._serverSyncs[i][0]==d){f=this._serverSyncs[i][1];break}}if(f!=null){e=(d?f:0);c=new Date()}else{var g=(d?d.apply(a,[]):null);c=new Date();e=(g?c.getTime()-g.getTime():0);this._serverSyncs.push([d,e])}var h=this._get(b,'timezone');h=(h==null?-c.getTimezoneOffset():h);b._since=this._get(b,'since');if(b._since!=null){b._since=this.UTCDate(h,this._determineTime(b._since,null));if(b._since&&e){b._since.setMilliseconds(b._since.getMilliseconds()+e)}}b._until=this.UTCDate(h,this._determineTime(this._get(b,'until'),c));if(e){b._until.setMilliseconds(b._until.getMilliseconds()+e)}b._show=this._determineShow(b)},_destroyCountdown:function(a){var b=$(a);if(!b.hasClass(this.markerClassName)){return}this._removeTarget(a);b.removeClass(this.markerClassName).empty();$.removeData(a,w)},_pauseCountdown:function(a){this._hold(a,'pause')},_lapCountdown:function(a){this._hold(a,'lap')},_resumeCountdown:function(a){this._hold(a,null)},_hold:function(a,b){var c=$.data(a,w);if(c){if(c._hold=='pause'&&!b){c._periods=c._savePeriods;var d=(c._since?'-':'+');c[c._since?'_since':'_until']=this._determineTime(d+c._periods[0]+'y'+d+c._periods[1]+'o'+d+c._periods[2]+'w'+d+c._periods[3]+'d'+d+c._periods[4]+'h'+d+c._periods[5]+'m'+d+c._periods[6]+'s');this._addTarget(a)}c._hold=b;c._savePeriods=(b=='pause'?c._periods:null);$.data(a,w,c);this._updateCountdown(a,c)}},_getTimesCountdown:function(a){var b=$.data(a,w);return(!b?null:(!b._hold?b._periods:this._calculatePeriods(b,b._show,this._get(b,'significant'),new Date())))},_get:function(a,b){return(a.options[b]!=null?a.options[b]:$.countdown._defaults[b])},_determineTime:function(k,l){var m=function(a){var b=new Date();b.setTime(b.getTime()+a*1000);return b};var n=function(a){a=a.toLowerCase();var b=new Date();var c=b.getFullYear();var d=b.getMonth();var e=b.getDate();var f=b.getHours();var g=b.getMinutes();var h=b.getSeconds();var i=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;var j=i.exec(a);while(j){switch(j[2]||'s'){case's':h+=parseInt(j[1],10);break;case'm':g+=parseInt(j[1],10);break;case'h':f+=parseInt(j[1],10);break;case'd':e+=parseInt(j[1],10);break;case'w':e+=parseInt(j[1],10)*7;break;case'o':d+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break;case'y':c+=parseInt(j[1],10);e=Math.min(e,$.countdown._getDaysInMonth(c,d));break}j=i.exec(a)}return new Date(c,d,e,f,g,h,0)};var o=(k==null?l:(typeof k=='string'?n(k):(typeof k=='number'?m(k):k)));if(o)o.setMilliseconds(0);return o},_getDaysInMonth:function(a,b){return 32-new Date(a,b,32).getDate()},_normalLabels:function(a){return a},_generateHTML:function(c){var d=this._get(c,'significant');c._periods=(c._hold?c._periods:this._calculatePeriods(c,c._show,d,new Date()));var e=false;var f=0;var g=d;var h=$.extend({},c._show);for(var i=Y;i<=S;i++){e|=(c._show[i]=='?'&&c._periods[i]>0);h[i]=(c._show[i]=='?'&&!e?null:c._show[i]);f+=(h[i]?1:0);g-=(c._periods[i]>0?1:0)}var j=[false,false,false,false,false,false,false];for(var i=S;i>=Y;i--){if(c._show[i]){if(c._periods[i]){j[i]=true}else{j[i]=g>0;g--}}}var k=this._get(c,'compact');var l=this._get(c,'layout');var m=(k?this._get(c,'compactLabels'):this._get(c,'labels'));var n=this._get(c,'whichLabels')||this._normalLabels;var o=this._get(c,'timeSeparator');var p=this._get(c,'description')||'';var q=function(a){var b=$.countdown._get(c,'compactLabels'+n(c._periods[a]));return(h[a]?c._periods[a]+(b?b[a]:m[a])+' ':'')};var r=function(a){var b=$.countdown._get(c,'labels'+n(c._periods[a]));return((!d&&h[a])||(d&&j[a])?'<span class="countdown_section"><span class="countdown_amount">'+c._periods[a]+'</span><span class="countdown_label">'+(b?b[a]:m[a])+'</span></span>':'')};return(l?this._buildLayout(c,h,l,k,d,j):((k?'<span class="countdown_row countdown_amount'+(c._hold?' countdown_holding':'')+'">'+q(Y)+q(O)+q(W)+q(D)+(h[H]?this._minDigits(c._periods[H],2):'')+(h[M]?(h[H]?o:'')+this._minDigits(c._periods[M],2):'')+(h[S]?(h[H]||h[M]?o:'')+this._minDigits(c._periods[S],2):''):'<span class="countdown_row countdown_show'+(d||f)+(c._hold?' countdown_holding':'')+'">'+r(Y)+r(O)+r(W)+r(D)+r(H)+r(M)+r(S))+'</span>'+(p?'<span class="countdown_row countdown_descr">'+p+'</span>':'')))},_buildLayout:function(c,d,e,f,g,h){var j=this._get(c,(f?'compactLabels':'labels'));var k=this._get(c,'whichLabels')||this._normalLabels;var l=function(a){return($.countdown._get(c,(f?'compactLabels':'labels')+k(c._periods[a]))||j)[a]};var m=function(a,b){return Math.floor(a/b)%10};var o={desc:this._get(c,'description'),sep:this._get(c,'timeSeparator'),yl:l(Y),yn:c._periods[Y],ynn:this._minDigits(c._periods[Y],2),ynnn:this._minDigits(c._periods[Y],3),y1:m(c._periods[Y],1),y10:m(c._periods[Y],10),y100:m(c._periods[Y],100),y1000:m(c._periods[Y],1000),ol:l(O),on:c._periods[O],onn:this._minDigits(c._periods[O],2),onnn:this._minDigits(c._periods[O],3),o1:m(c._periods[O],1),o10:m(c._periods[O],10),o100:m(c._periods[O],100),o1000:m(c._periods[O],1000),wl:l(W),wn:c._periods[W],wnn:this._minDigits(c._periods[W],2),wnnn:this._minDigits(c._periods[W],3),w1:m(c._periods[W],1),w10:m(c._periods[W],10),w100:m(c._periods[W],100),w1000:m(c._periods[W],1000),dl:l(D),dn:c._periods[D],dnn:this._minDigits(c._periods[D],2),dnnn:this._minDigits(c._periods[D],3),d1:m(c._periods[D],1),d10:m(c._periods[D],10),d100:m(c._periods[D],100),d1000:m(c._periods[D],1000),hl:l(H),hn:c._periods[H],hnn:this._minDigits(c._periods[H],2),hnnn:this._minDigits(c._periods[H],3),h1:m(c._periods[H],1),h10:m(c._periods[H],10),h100:m(c._periods[H],100),h1000:m(c._periods[H],1000),ml:l(M),mn:c._periods[M],mnn:this._minDigits(c._periods[M],2),mnnn:this._minDigits(c._periods[M],3),m1:m(c._periods[M],1),m10:m(c._periods[M],10),m100:m(c._periods[M],100),m1000:m(c._periods[M],1000),sl:l(S),sn:c._periods[S],snn:this._minDigits(c._periods[S],2),snnn:this._minDigits(c._periods[S],3),s1:m(c._periods[S],1),s10:m(c._periods[S],10),s100:m(c._periods[S],100),s1000:m(c._periods[S],1000)};var p=e;for(var i=Y;i<=S;i++){var q='yowdhms'.charAt(i);var r=new RegExp('\\{'+q+'<\\}(.*)\\{'+q+'>\\}','g');p=p.replace(r,((!g&&d[i])||(g&&h[i])?'$1':''))}$.each(o,function(n,v){var a=new RegExp('\\{'+n+'\\}','g');p=p.replace(a,v)});return p},_minDigits:function(a,b){a=''+a;if(a.length>=b){return a}a='0000000000'+a;return a.substr(a.length-b)},_determineShow:function(a){var b=this._get(a,'format');var c=[];c[Y]=(b.match('y')?'?':(b.match('Y')?'!':null));c[O]=(b.match('o')?'?':(b.match('O')?'!':null));c[W]=(b.match('w')?'?':(b.match('W')?'!':null));c[D]=(b.match('d')?'?':(b.match('D')?'!':null));c[H]=(b.match('h')?'?':(b.match('H')?'!':null));c[M]=(b.match('m')?'?':(b.match('M')?'!':null));c[S]=(b.match('s')?'?':(b.match('S')?'!':null));return c},_calculatePeriods:function(c,d,e,f){c._now=f;c._now.setMilliseconds(0);var g=new Date(c._now.getTime());if(c._since){if(f.getTime()<c._since.getTime()){c._now=f=g}else{f=c._since}}else{g.setTime(c._until.getTime());if(f.getTime()>c._until.getTime()){c._now=f=g}}var h=[0,0,0,0,0,0,0];if(d[Y]||d[O]){var i=$.countdown._getDaysInMonth(f.getFullYear(),f.getMonth());var j=$.countdown._getDaysInMonth(g.getFullYear(),g.getMonth());var k=(g.getDate()==f.getDate()||(g.getDate()>=Math.min(i,j)&&f.getDate()>=Math.min(i,j)));var l=function(a){return(a.getHours()*60+a.getMinutes())*60+a.getSeconds()};var m=Math.max(0,(g.getFullYear()-f.getFullYear())*12+g.getMonth()-f.getMonth()+((g.getDate()<f.getDate()&&!k)||(k&&l(g)<l(f))?-1:0));h[Y]=(d[Y]?Math.floor(m/12):0);h[O]=(d[O]?m-h[Y]*12:0);f=new Date(f.getTime());var n=(f.getDate()==i);var o=$.countdown._getDaysInMonth(f.getFullYear()+h[Y],f.getMonth()+h[O]);if(f.getDate()>o){f.setDate(o)}f.setFullYear(f.getFullYear()+h[Y]);f.setMonth(f.getMonth()+h[O]);if(n){f.setDate(o)}}var p=Math.floor((g.getTime()-f.getTime())/1000);var q=function(a,b){h[a]=(d[a]?Math.floor(p/b):0);p-=h[a]*b};q(W,604800);q(D,86400);q(H,3600);q(M,60);q(S,1);if(p>0&&!c._since){var r=[1,12,4.3482,7,24,60,60];var s=S;var t=1;for(var u=S;u>=Y;u--){if(d[u]){if(h[s]>=t){h[s]=0;p=1}if(p>0){h[u]++;p=0;s=u;t=1}}t*=r[u]}}if(e){for(var u=Y;u<=S;u++){if(e&&h[u]){e--}else if(!e){h[u]=0}}}return h}});function extendRemove(a,b){$.extend(a,b);for(var c in b){if(b[c]==null){a[c]=null}}return a}$.fn.countdown=function(a){var b=Array.prototype.slice.call(arguments,1);if(a=='getTimes'||a=='settings'){return $.countdown['_'+a+'Countdown'].apply($.countdown,[this[0]].concat(b))}return this.each(function(){if(typeof a=='string'){$.countdown['_'+a+'Countdown'].apply($.countdown,[this].concat(b))}else{$.countdown._attachCountdown(this,a)}})};$.countdown=new Countdown()})(jQuery);

/*
 * Tabiy
 * Shopiy.com
 */
(function($) {
var tabsCount = 1;
$.fn.Tabiy = function(){
	var container = $(this);
	var box = container.find('.box[id!=""]');
	var otherBox = container.find('.box[id=""]');
	if (box.length > 0) {
		var tabs = '<div class="tab_wrapper"><p class="tabs" id="tabs-' + tabsCount + '"></p><div class="extra"></div></div>';
		container.prepend(tabs);
		var tabsWrap = $('#tabs-' + tabsCount + '');
		var tabsHeight = tabsWrap.outerHeight() + 7;
		box.hide().css({paddingTop: (tabsHeight), marginTop: -tabsHeight});
		box.find('.hd').hide();
		box.each(function(n) {
			var supText = $(this).find('.hd .extra em').text();
			supText = (supText.length < 1) ? '' : '<em class="number">' + supText + '</em>';
			tabsWrap.append('<a href="#' + $(this).attr('id') + '" id="tab-' + $(this).attr('id') + '"><span>' + $(this).find('.hd h3').text() + supText + '</span></a>');
		});
		otherBox.detach();
		container.append(otherBox);
		var linkBox = container.find(location.hash);
		if (linkBox.length == 1) {
			$('#tab-' + location.hash.slice(1) + '').addClass('current');
			linkBox.show();
		} else {
			$('#tabs-' + tabsCount + ' a:eq(0)').addClass('current');
			box.eq(0).show();
		};
		$('#tabs-' + tabsCount + ' a').click(function(){
			$(this).addClass('current').siblings().removeClass('current');
			container.find('.box[id!=""]').hide();
			$('#' + $(this).attr('id').slice(4) + '').fadeIn('fast');
			return false;
		});
	};
	tabsCount++;
};
})(jQuery);
/*
 * Slidiy
 * Shopiy.com
 */
(function($) {
$.fn.Slidiy = function(){
	var slide = $(this);
	var item = slide.find('li');
	var currentSlide = -1;
	var prevSlide = null;
	var interval = null;
	var html = '<p class="triggers">';
	item.css({position:'absolute'}).slice(1).hide();
	for (var i = 0 ;i <= item.length - 1; i++){
		var url = slide.find('a').eq(i).attr('href');
		html += '<a href="'+ url +'" id="slide'+ i +'">'+ (i+1) +'</a>' ;
	}
	html += '</p>';
	slide.find('ul').after(html);
	var triggers = slide.find('.triggers');
	//slide.hover(function(){triggers.stop().animate({bottom:'0'},200)},function(){triggers.stop().animate({bottom:'-40px'},200)});
	for (var i = 0 ;i <= item.length - 1; i++){
		$('#slide'+i).bind('mouseover',{index:i},function(event){
			currentSlide = event.data.index;
			gotoSlide(event.data.index);
		});
	};
	if (item.length <= 1){
		triggers.hide();
	}
	nextSlide();
	function nextSlide (){
		if (currentSlide >= item.length -1){
			currentSlide = 0;
		}else{
			currentSlide++
		}
		gotoSlide(currentSlide);
	}
	function gotoSlide(slideNum){
		if (slideNum != prevSlide){
			if (prevSlide != null){
				item.eq(prevSlide).css({opacity:'1',zIndex:'0'}).stop().animate({opacity: 0}, 300);
				$('#slide'+prevSlide).removeClass('current');
			}
			$('#slide'+currentSlide).addClass('current');
			$('#slide'+slideNum).addClass('current');
			$('#slide'+prevSlide).removeClass('current');
			item.eq(slideNum).show().css({opacity:'0',zIndex:'1'}).stop().animate({opacity: 1}, 300);
			prevSlide = currentSlide;
			if (interval != null){
				clearInterval(interval);
			}
			interval = setInterval(nextSlide, 3000);
		}
	}
};
})(jQuery);

/* mSlidiy */
(function($) {
	$.fn.mSlidiy = function(options){
		var defaults = {
			num:			4,
			controls:		true,
			vertical:		false,
			speed:			200,
			step:			4,
			auto:			false,
			pause:			2000,
			continuous:		true,
			controlsFade:	false,
			html:			'<p class="controls"><a href="#" class="prev">Previous</a><a href="#" class="next">Next</a></p>'
		};
		var options = $.extend(defaults, options);
		this.each(function() {
			var obj = $(this);
			$('.clearer', obj).remove();
			$('.first_child', obj).removeClass('first_child');
			var s = $('li', obj).length;
			var w = $('li', obj).outerWidth();
			var h = $('li', obj).outerHeight();
			var maxh = 0;
			$('li', obj).each(function() {
				var thisHeight = $(this).outerHeight();
				if(thisHeight > maxh) {
					maxh = thisHeight;
				}
			});
			var maxih = 0;
			$('li', obj).each(function() {
				var thisInnerHeight = $(this).height();
				if(thisInnerHeight > maxih) {
					maxih = thisInnerHeight;
				}
			});
			if(!options.vertical) {
				obj.css('overflow-x','hidden');
				obj.width(w*options.num);
				//obj.height(maxh);
				$('ul', obj).css('width',s*w);
			} else {
				obj.css('overflow-y','hidden');
				obj.width(w);
				if (s < options.num) {
					obj.height(maxh*s);
				} else {
					obj.height(maxh*options.num);
				}
				$('ul', obj).css('height',s*maxh);
				//$('li', obj).css({height: maxih, overflow:'hidden'});
			};
			var ts = s-1;
			var t = 0;
			if(options.controls && (s>options.num)){obj.after(options.html);};
			var nextBtn = $('.next', obj.parent());
			var prevBtn = $('.prev', obj.parent());
			nextBtn.click(function(){animate('next',true);});
			prevBtn.click(function(){animate('prev',true);});
			//$('li', obj).first().addClass('current');
			//$('li', obj).mouseover(function() {
				//$(this).addClass('current').siblings().removeClass('current');
			//});
			
			function animate(dir,clicked){
				var ot = t;
				switch(dir){
					case 'next':
						t = (ot+options.num>ts) ? (options.continuous ? 0 : ot) : t+options.step;
						break;
					case 'prev':
						t = (t<=0) ? (options.continuous ? ts-options.num+1 : 0) : t-options.step;
						break;
					default:
						break;
				};	
				
				var diff = Math.abs(ot-t);
				var speed = diff*options.speed;
				if(!options.vertical) {
					p = (t*w*-1);
					$('ul',obj).animate(
						{ marginLeft: p },
						speed
					);
				} else {
					p = (t*h*-1);
					$('ul',obj).animate(
						{ marginTop: p },
						speed
					);
				};
				
				if(!options.continuous && options.controlsFade){
					if(t==ts-ts%options.num){
						nextBtn.hide();
					} else {
						nextBtn.show();
					};
					if(t==0){
						prevBtn.hide();
					} else {
						prevBtn.show();
					};
				};
				
				if(clicked) clearTimeout(timeout);
				if(options.auto && dir=='next' && !clicked){;
					timeout = setTimeout(function(){
						animate('next',false);
					},diff*options.speed+options.pause);
				};
				
			};
			var timeout;
			if(options.auto){
				timeout = setTimeout(function(){
					animate('next',false);
				},options.pause);
			};		
		
			if(!options.continuous && options.controlsFade){
				prevBtn.hide();
			};
			
		});
	  
	};

})(jQuery);

(function($) {
$.fn.Formiy = function(){
	var area = $(this);
	var label = area.find('label');
	var input = area.find('input');
	label.has(':checked').addClass('checked');
	label.hover(function(){
		$(this).toggleClass('hover');
	});
	label.find('input').click(function(event) {
		var label = $(this).parent();
		if (label.children('input').is(':radio')) {
			label.addClass('checked').siblings().removeClass('checked');
		} else if (label.children('input').is(':checkbox')) {
			label.toggleClass('checked');
		}
	});
};
})(jQuery);

/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function (key, value, options) {
    
    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        
        value = String(value);
        
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};