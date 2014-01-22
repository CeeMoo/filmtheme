/*********************
//* jQuery Multi Level CSS Menu #2- By Dynamic Drive: http://www.dynamicdrive.com/
//* Last update: Nov 7th, 08': Limit # of queued animations to minmize animation stuttering
//* Menu avaiable at DD CSS Library: http://www.dynamicdrive.com/style/
*********************/

//Update: April 12th, 10: Fixed compat issue with jquery 1.4x

//Specify full URL to down and right arrow images (23 is padding-right to add to top level LIs with drop downs):
var arrowimages={down:['downarrowclass', 'Themes/filmtheme/images/theme/down.gif', 23], right:['rightarrowclass', 'Themes/filmtheme/images/theme/right.gif']}

var jqueryslidemenu={

animateduration: {over: 200, out: 100}, //duration of slide in/ out animation, in milliseconds

buildmenu:function(menuid, arrowsvar){
	jQuery(document).ready(function($){
		var $mainmenu=$("#"+menuid+">ul")
		var $headers=$mainmenu.find("ul").parent()
		$headers.each(function(i){
			var $curobj=$(this)
			var $subul=$(this).find('ul:eq(0)')
			this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
			this.istopheader=$curobj.parents("ul").length==1? true : false
			$subul.css({top:this.istopheader? this._dimensions.h+"px" : 0})
			$curobj.children("a:eq(0)").css(this.istopheader? {paddingRight: arrowsvar.down[2]} : {}).append(
				'<img src="'+ (this.istopheader? arrowsvar.down[1] : arrowsvar.right[1])
				+'" class="' + (this.istopheader? arrowsvar.down[0] : arrowsvar.right[0])
				+ '" style="border:0;" />'
			)
			$curobj.hover(
				function(e){
					var $targetul=$(this).children("ul:eq(0)")
					this._offsets={left:$(this).offset().left, top:$(this).offset().top}
					var menuleft=this.istopheader? 0 : this._dimensions.w
					menuleft=(this._offsets.left+menuleft+this._dimensions.subulw>$(window).width())? (this.istopheader? -this._dimensions.subulw+this._dimensions.w : -this._dimensions.w) : menuleft
					if ($targetul.queue().length<=1) //if 1 or less queued animations
						$targetul.css({left:menuleft+"px", width:this._dimensions.subulw+'px'}).slideDown(jqueryslidemenu.animateduration.over)
				},
				function(e){
					var $targetul=$(this).children("ul:eq(0)")
					$targetul.slideUp(jqueryslidemenu.animateduration.out)
				}
			) //end hover
			$curobj.click(function(){
				$(this).children("ul:eq(0)").hide()
			})
		}) //end $headers.each()
		$mainmenu.find("ul").css({display:'none', visibility:'visible'})
	}) //end document.ready
}
}

//build menu with ID="myslidemenu" on page:
jqueryslidemenu.buildmenu("myslidemenu", arrowimages)

function smf_codeBoxFix()
{var codeFix=document.getElementsByTagName('code');for(var i=codeFix.length-1;i>=0;i--)
{if(is_webkit&&codeFix[i].offsetHeight<20)
codeFix[i].style.height=(codeFix[i].offsetHeight+20)+'px';else if(is_ff&&(codeFix[i].scrollWidth>codeFix[i].clientWidth||codeFix[i].clientWidth==0))
codeFix[i].style.overflow='scroll';else if('currentStyle'in codeFix[i]&&codeFix[i].currentStyle.overflow=='auto'&&(codeFix[i].currentStyle.height==''||codeFix[i].currentStyle.height=='auto')&&(codeFix[i].scrollWidth>codeFix[i].clientWidth||codeFix[i].clientWidth==0)&&(codeFix[i].offsetHeight!=0))
codeFix[i].style.height=(codeFix[i].offsetHeight+24)+'px';}}
if((is_ie&&!is_ie4)||is_webkit||is_ff)
addLoadEvent(smf_codeBoxFix);function smc_toggleImageDimensions()
{var oImages=document.getElementsByTagName('IMG');for(oImage in oImages)
{if(oImages[oImage].className==undefined||oImages[oImage].className.indexOf('bbc_img resized')==-1)
continue;oImages[oImage].style.cursor='pointer';oImages[oImage].onclick=function(){this.style.width=this.style.height=this.style.width=='auto'?null:'auto';};}}
addLoadEvent(smc_toggleImageDimensions);function smf_addButton(sButtonStripId,bUseImage,oOptions)
{var oButtonStrip=document.getElementById(sButtonStripId);var aItems=oButtonStrip.getElementsByTagName('span');if(aItems.length>0)
{var oLastSpan=aItems[aItems.length-1];oLastSpan.className=oLastSpan.className.replace(/\s*last/,'position_holder');}
var oButtonStripList=oButtonStrip.getElementsByTagName('ul')[0];var oNewButton=document.createElement('li');setInnerHTML(oNewButton,'<a href="'+oOptions.sUrl+'" '+('sCustom'in oOptions?oOptions.sCustom:'')+'><span class="last"'+('sId'in oOptions?' id="'+oOptions.sId+'"':'')+'>'+oOptions.sText+'</span></a>');oButtonStripList.appendChild(oNewButton);}
var smf_addListItemHoverEvents=function()
{var cssRule,newSelector;for(var iStyleSheet=0;iStyleSheet<document.styleSheets.length;iStyleSheet++)
for(var iRule=0;iRule<document.styleSheets[iStyleSheet].rules.length;iRule++)
{oCssRule=document.styleSheets[iStyleSheet].rules[iRule];if(oCssRule.selectorText.indexOf('LI:hover')!=-1)
{sNewSelector=oCssRule.selectorText.replace(/LI:hover/gi,'LI.iehover');document.styleSheets[iStyleSheet].addRule(sNewSelector,oCssRule.style.cssText);}}
var oListItems=document.getElementsByTagName('LI');for(oListItem in oListItems)
{oListItems[oListItem].onmouseover=function(){this.className+=' iehover';};oListItems[oListItem].onmouseout=function(){this.className=this.className.replace(new RegExp(' iehover\\b'),'');};}}
if(is_ie7down&&'attachEvent'in window)
window.attachEvent('onload',smf_addListItemHoverEvents);
