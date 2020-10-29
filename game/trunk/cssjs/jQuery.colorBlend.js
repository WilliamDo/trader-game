/**
 * jQuery Plugin colorBlend v1.5.0
 * Requires jQuery 1.2.1+ (Not tested with earlier versions).
 * Based on the Fade plugin http://plugins.jquery.com/project/fade
 * Code losely based on the Cycle plugin http://plugins.jquery.com/project/cycle It was a great resource in creating this one) 
 * Copyright (c) 2007-2008 Aaron E. [jquery at happinessinmycheeks dot com] 
 * 
 *	@param: Object Array. Arguments need to be in object notation.
 *	Returns: jQuery.
 *	Options:	
 *		param:		What css color option you wish to fade. 
 *					Such as "background-color", "color", "boarder-color", "scrollbar-face-color" etc.
 *					(default: "background-color).
 *		fps:		Frames per second (default: 30).
 *		cycles:		How many times you want the object to fade. 0 = Infinite. (default: 0).
 *		random:		Will transition from a random color to a random color. (default: false).
 *					Note: Will change isFade to false.
 *		isFade:		Will fade from the original color and back to the original color. (default: true).
 *					Note: Cannont set to true if random is set to true.
 *		fromColor:	Starting color. accepts RGB, Hex, Name values. (default: "current").
 *					Will be overwritten if random is set to true. Also accepts "random" as an option.
 *		toColor:	Ending color. Same as above (default: "opposite").
 *		alpha:		Opacity of element! accepts a comma delimited string. Such as "0,100" or "100,40" also now accepts single string to just set the opacity.
 *
 *	Examples: 
 *		$("body").colorBlend([{fromColor:"black", toColor:"white", param:"color"}]);
 *		var myColors = [
 *			{param:'color', fromColor:"white", toColor:"black"},
 *			{param:'background-color', random: true, alpha:"20,75"},
 *			{param:'border-left-color', fromColor:"random", toColor:"black"},
 *			{param:'border-right-color', fromColor:"white", toColor:"black"},
 *			{param:'border-top-color', fromColor:"white", toColor:"black"},
 *			{param:'border-bottom-color', fromColor:"white", toColor:"tomato"}
 *		];
 *		$("tr").colorBlend(myColors);
 *
 *	Known issues: 
 *			* If used on a lot of objects it can cause major browser slowdown and it will eat a lot of cpu. 
 *			* Still one flickering bug when it comes to opacity. Trying to track it down. 
 *
 *	Additions:
 *		1.0.2
 *			* Added "parent" as a valid color value. Will check parents until valid color is found. 
 *				defaults to white if there are no parents with color.
 *		1.0.3
 *			* Added Alpha/Opacity blending! Add alpha:"0,100" to list of parameters. 
 *				Note: Will change the opacity of element only, not the property!
 *				If you only want text to appear and dissapear, you'll have to put it in it's own element, otherise the whole
 *				element will fade, not just your text.
 *		1.0.4
 *			* Alpha will now take just one argument alpha:"30" if you want to just change the alpha and not have it animate. 
 *			* Current is now the default fromColor value. The current value will get the current color of the element. If current is transparent, it will get the parent color.
 *			* Opposite is now the default toColor value.
 *
 *		1.3.0
 *			* Added Queueing ability, so an animation will take arguments and process them once they are available. 
 *			* Added Action parameter available arguments are stop, pause, and resume. Resume continues a paused animation. Where stop lets you assign a whole new animation to the element.
 *			* Added isQueue as an option allows you to decide if you want an option to be queued or not
 *
 *		1.4.0
 *			* Added pause all, stop all, resume all.
 *			* Have objects stored in an non-named array for traversing.
 *
 *	Bugs fixed:
 *		1.0.1 
 *			* Undesired flickering effect if colorBlend was called multiple times on the same css parameter.
 *		1.0.2 
 *			* Noticed element would keep color attributes in certain circumstances.
 *		1.0.4
 *			* Fixed bug where under certain conditions the color would flicker. 
 *		1.0.5
 *			* Great find by cratchit and he supplied the fix. Can now call colorBlend without any options. 
 *		1.2.0
 *			* Flicker fix in 1.0.4 caused other issues. Fixed for good. 
 *			* Found that if you try to get current color from scroll bar, it blows up. Added check for undefined as a color. Defaults to white.
 *		1.3.0
 *			* Found MORE flickering issues, and fixed them. I guess it's not over until the fat lady sings. Didn't see any more flicking, but I don't hear a fat lady.
 *		1.5.0
 *			* In my ignorance I noticed that alpha is taken care of quite nicely by jquery itself. No need to fix what isn't broke. Removed the custom stuff I had placed in.
 *			* Found issue where if pausing and resuming something repeatitivly it might not sync up and know that it's stopped. Added isPOrS variable to check if paused or stopped. Seems to work. 
 */
  
(function($) {
	var ver = '1.5.0';
	var gObj = [];
	var q = 0;
	$.fn.colorBlend = function(opts) {
		if(!opts) { opts = [{}]; }
		
		var arrySelected = [];
		this.each(function() {
			arrySelected[arrySelected.length] = $.data($(this).get(0));
		});

		return this.each(function() {
			var uId = $.data($(this).get(0));
			var $cont = $(this);
			var isFlagAll = false;
			
			if(udf(gObj[uId])) {
				gObj[uId] = [];
			}

			$.each(opts, function(i, v){
				var isFound = false;
				opts[i] = $.extend({}, $.fn.colorBlend.defaults, opts[i]);
				opts[i].queue = [];
				opts[i].internals = $.extend({}, $.fn.colorBlend.internals);
				opts[i].parent = $cont;

				if(opts[i].param == "all") {
					isFlagAll = FlagAll(opts[i].action);
				}
								
				$.each(gObj[uId], function(j, w) {
					if(gObj[uId][j].param.toLowerCase() == opts[i].param.toLowerCase() 
					|| opts[i].param.toLowerCase() == 'all') {
						if(!gObj[uId][j].internals.animating) {
							gObj[uId].splice(j, 1, setOptions(opts[i]));
						}
						isFound = true;
						return false;
					}
				});
				
				if(!isFound) {
					gObj[uId].push(setOptions(opts[i]));
				}
			});

			if(!isFlagAll) {
				$.each(gObj[uId], function(i, v){
					var ani = gObj[uId][i].internals.animating;
					var pausedOrStopped = gObj[uId][i].internals.isPOrS;
					
					$.each(opts, function(j, w) {
						if(gObj[uId][i].param.toLowerCase() != opts[j].param.toLowerCase()) {
							return true;
						}

						switch(opts[j].action) {
							case "stop":
							case "pause":
								clearTimeout(gObj[uId][i].internals.tId);
								gObj[uId][i].internals.isPOrS = true;
								pausedOrStopped = true;
								if(opts[j].action == "stop") {
									gObj[uId][i].internals.animating = false;
								}
							break;
							case "resume":
								ani = true;
								pausedOrStopped = false;
								gObj[uId][i].internals.isPOrS = false;
								go(gObj[uId][i]);
							break;
							default:
								if(!ani) {
									gObj[uId][i] = setOptions(opts[j]);
								} else {
									if(gObj[uId][i].isQueue && gObj[uId][i].cycles > 0) {
										gObj[uId][i].queue.push(setOptions(opts[j]));
									}
								}
							break;
						}
					});
					
					if(!ani && !pausedOrStopped) {
						go(gObj[uId][i]);
					} 
				});
			}
		});
		
		function FlagAll(action) {
			var res = false;
			$.each(arrySelected, function(i, v) {
				var curObj = gObj[v];
				$.each(curObj, function(j, w) {
					switch(action) {
						case "stop":
						case "pause": 
							res = true;
							clearTimeout(curObj[j].internals.tId);
							curObj[j].internals.isPOrS = true;
							if(action == "stop") {
								curObj[j].internals.animating = false;
							}
						break;
						case "resume": 
							res = true;
							curObj[j].internals.isPOrS = false;
							go(curObj[j]);
						break;
					}
				});
			});

			return res;
		};
	};

	$.fn.colorBlend.defaults = {
		fps:30,
		duration:1000,
		param:"background-color",
		cycles:0,
		random:false,
		isFade:true,
		fromColor:"current",
		toColor:"opposite",
		alpha:"100,100",
		action:"",
		isQueue:true
	};
	
	$.fn.colorBlend.internals = {
		aniArray:  [],
		alphaArry: [],
		pos: 0,
		currentCycle: 0,
		direction: 1,
		frames: 0,
		delay: 0,
		fromRand: false,
		toRand: false,
		animating: false,
		isAlpha: false,
		tId: 0,
		isPOrS: false
	};

	function setOptions(Opts) {
		if(!Opts.internals.animating) {
			var alphaParam = Opts.alpha.split(",");

			switch(Opts.fromColor.toLowerCase()) {
				case "current":
					Opts.fromColor = Opts.parent.css(Opts.param);
					break;
				case "parent":
				case "transparent":
					Opts.fromColor = checkParentColor(Opts.parent, Opts.param);
					break;
				case "opposite":
					Opts.fromColor = OppositeColor(Opts.toColor);
					break;
				case "random":
					Opts.fromColor = rndColor();
					Opts.internals.fromRand = true;
					break;
			}
				
			switch(Opts.toColor.toLowerCase()) {
				case "current":
					Opts.toColor = Opts.parent.css(Opts.param);
					break;
				case "parent":
				case "transparent":
					Opts.toColor = checkParentColor(Opts.parent, Opts.param);
					break;
				case "opposite":
					Opts.toColor = OppositeColor(Opts.fromColor);
					break;
				case "random":
					Opts.toColor = rndColor();
					Opts.internals.toRand = true;
					break;
			}

			if(Opts.toColor == Opts.fromColor) {
				Opts.parent.css(Opts.param, Opts.toColor);
			}

			Opts.internals.currentCycle = Opts.cycles > 0 ? Opts.cycles : 0;
			Opts.internals.frames = Math.floor(Opts.fps * (Opts.duration / 1000));
			Opts.internals.delay = Math.floor(Opts.duration / (Opts.internals.frames+1));

			if(Opts.random) {
				Opts.isFade = false;
				Opts.fromColor = rndColor();
				Opts.toColor = rndColor();
			}
				
			if(Opts.isFade) {
				Opts.internals.currentCycle = Opts.internals.currentCycle * 2;
				Opts.internals.delay = Math.floor(Opts.internals.delay / 2);
				Opts.internals.frames = Math.floor(Opts.internals.frames / 2);
			}					
				
			if((alphaParam.length == 1 && alphaParam[0] != 100)
			|| (alphaParam.length == 2 && (alphaParam[0] != 100 || alphaParam[1] != 100))) { 
				if(alphaParam.length == 2) {
					if(parseInt(alphaParam[0]) != parseInt(alphaParam[1])) {
						Opts.internals.isAlpha = true;
						Opts.internals.alphaArry = buildAlphaAni(alphaParam[0], alphaParam[1], Opts.internals);
					} else {
						setAlpha(Opts.parent, alphaParam[0]);
					}
				} else { 
					if(parseInt(alphaParam[0])) {
						setAlpha(Opts.parent, alphaParam[0]);
					}
				}
			}
			
			Opts.internals.aniArray = buildAnimation(Opts.fromColor, Opts.toColor, Opts.internals);
			return Opts;
		}
	}

	function go(Opts) {
		if(!Opts.internals.isPOrS) {
			var sendStop = false;

			Opts.internals.animating = true;

			if(Opts.fromColor != Opts.toColor) {
				Opts.parent.css(Opts.param, Opts.internals.aniArray[Opts.internals.pos]);
			} 
			
			if(Opts.internals.isAlpha) {
				setAlpha(Opts.parent, Opts.internals.alphaArry[Opts.internals.pos]);
			}

			Opts.internals.pos += Opts.internals.direction; 

			if(Opts.internals.pos < 0 || Opts.internals.pos >= Opts.internals.aniArray.length) {
				Opts.internals.currentCycle -= Opts.internals.currentCycle != 0 ? 1 : 0;
				Opts.internals.direction = Opts.internals.direction * -1;
				Opts.internals.pos += Opts.internals.direction;

				if(Opts.random) {
					Opts.fromColor = Opts.toColor;
					Opts.toColor = rndColor();
					Opts.internals.aniArray = buildAnimation(Opts.fromColor, Opts.toColor, Opts.internals);
				}

				if(!Opts.isFade) {
					Opts.internals.direction = 1;
					Opts.internals.pos = 0;
				}

				if(Opts.internals.currentCycle == 0 && Opts.cycles > 0) {
					sendStop = true;
				}
			}

			if(!sendStop) {
				Opts.internals.tId = setTimeout(function(){go(Opts);}, Opts.internals.delay);
			} else {
				clearTimeout(Opts.internals.tId);
				Opts.internals.tId = 0;
				if(Opts.isQueue && Opts.queue.length > 0) {
					var tmp = Opts.queue.concat();
					tmp.splice(0,1);
					Opts = $.extend(Opts, Opts.queue.shift());
					Opts.queue = tmp.concat();
					Opts.internals.tId = setTimeout(function(){go(Opts);}, Opts.internals.delay);
				} else {
					Opts.internals.animating = false;
					Opts.internals.isPOrS = true;
				}
			}
		}
	}

	function setAlpha(elm, opacity) {
		elm.css("opacity", parseFloat(opacity / 100));
	}

	function buildAlphaAni(startOpacity, endOpacity, intOpts) {
		var frames = intOpts.frames;
		var frame = 0;
		var res = [];
		var h = 0;
		for(frame = 0;frame<=frames;frame++) {
			h = Math.floor(startOpacity * ((frames-frame)/frames) + endOpacity * (frame/frames));
			res[res.length] = h
		}
		
		if(h != endOpacity) {
			res[res.length] = parseInt(endOpacity);
		}
		
		return res;
	}

	function buildAnimation(startColor, endColor, intOpts) {
		var frames = intOpts.frames;
		var frame = 0;
		var r,g,b,h;
		var res = [];

		startColor = toHexColor(startColor);
		endColor = toHexColor(endColor);		
		var fc = ColorHexToDec(startColor).split(', '); 
		var tc = ColorHexToDec(endColor).split(', ');
		
		for(frame = 0;frame<=frames;frame++) {
			r = Math.floor(fc[0] * ((frames-frame)/frames) + tc[0] * (frame/frames));
			g = Math.floor(fc[1] * ((frames-frame)/frames) + tc[1] * (frame/frames));
			b = Math.floor(fc[2] * ((frames-frame)/frames) + tc[2] * (frame/frames));
			h = ColorDecToHex(r, g, b);
			res[res.length] = h;
		}

		if(h.toLowerCase() != endColor.toLowerCase()) {
			res[res.length] = endColor;
		}
		
		return res;
	}

	function getHexColorByName(colorName) {
		return allColorsByName()[colorName.toLowerCase()];
	}

	function allColorsByName() {
		var colors = [];
		colors["aliceblue"] = "F0F8FF"; colors["antiquewhite"] = "FAEBD7"; colors["aqua"] = "00FFFF"; colors["aquamarine"] = "7FFFD4";
		colors["azure"] = "F0FFFF"; colors["beige"] = "F5F5DC"; colors["bisque"] = "FFE4C4"; colors["black"] = "000000";
		colors["blanchedalmond"] = "FFEBCD"; colors["blue"] = "0000FF"; colors["blueviolet"] = "8A2BE2"; colors["brown"] = "A52A2A";
		colors["burlywood"] = "DEB887"; colors["cadetblue"] = "5F9EA0"; colors["chartreuse"] = "7FFF00"; colors["chocolate"] = "D2691E";
		colors["coral"] = "FF7F50"; colors["cornflowerblue"] = "6495ED"; colors["cornsilk"] = "FFF8DC"; colors["crimson"] = "DC143C";
		colors["cyan"] = "00FFFF"; colors["darkblue"] = "00008B"; colors["darkcyan"] = "008B8B"; colors["darkgoldenrod"] = "B8860B";
		colors["darkgray"] = "A9A9A9"; colors["darkgreen"] = "006400"; colors["darkkhaki"] = "BDB76B"; colors["darkmagenta"] = "8B008B";
		colors["darkolivegreen"] = "556B2F"; colors["darkorange"] = "FF8C00"; colors["darkorchid"] = "9932CC"; colors["darkred"] = "8B0000";
		colors["darksalmon"] = "E9967A"; colors["darkseagreen"] = "8FBC8F"; colors["darkslateblue"] = "483D8B"; colors["darkslategray"] = "2F4F4F";
		colors["darkturquoise"] = "00CED1"; colors["darkviolet"] = "9400D3"; colors["deeppink"] = "FF1493"; colors["deepskyblue"] = "00BFFF";
		colors["dimgray"] = "696969"; colors["dodgerblue"] = "1E90FF"; colors["firebrick"] = "B22222"; colors["floralwhite"] = "FFFAF0";
		colors["forestgreen"] = "228B22"; colors["fuchsia"] = "FF00FF"; colors["gainsboro"] = "DCDCDC"; colors["ghostwhite"] = "F8F8FF";
		colors["gold"] = "FFD700"; colors["goldenrod"] = "DAA520"; colors["gray"] = "808080"; colors["green"] = "008000";
		colors["greenyellow"] = "ADFF2F"; colors["honeydew"] = "F0FFF0"; colors["hotpink"] = "FF69B4"; colors["indianred"] = "CD5C5C";
		colors["indigo"] = "4B0082"; colors["ivory"] = "FFFFF0"; colors["khaki"] = "F0E68C"; colors["lavender"] = "E6E6FA";
		colors["lavenderblush"] = "FFF0F5"; colors["lawngreen"] = "7CFC00"; colors["lemonchiffon"] = "FFFACD"; colors["lightblue"] = "ADD8E6";
		colors["lightcoral"] = "F08080"; colors["lightcyan"] = "E0FFFF"; colors["lightgoldenrodyellow"] = "FAFAD2"; colors["lightgreen"] = "90EE90";
		colors["lightgrey"] = "D3D3D3"; colors["lightpink"] = "FFB6C1"; colors["lightsalmon"] = "FFA07A"; colors["lightseagreen"] = "20B2AA";
		colors["lightskyblue"] = "87CEFA"; colors["lightslategray"] = "778899"; colors["lightsteelblue"] = "B0C4DE"; colors["lightyellow"] = "FFFFE0";
		colors["lime"] = "00FF00"; colors["limegreen"] = "32CD32"; colors["linen"] = "FAF0E6"; colors["magenta"] = "FF00FF";
		colors["maroon"] = "800000"; colors["mediumaquamarine"] = "66CDAA"; colors["mediumblue"] = "0000CD"; colors["mediumorchid"] = "BA55D3";
		colors["mediumpurple"] = "9370DB"; colors["mediumseagreen"] = "3CB371"; colors["mediumslateblue"] = "7B68EE"; colors["mediumspringgreen"] = "00FA9A";
		colors["mediumturquoise"] = "48D1CC"; colors["mediumvioletred"] = "C71585"; colors["midnightblue"] = "191970"; colors["mintcream"] = "F5FFFA";
		colors["mistyrose"] = "FFE4E1"; colors["moccasin"] = "FFE4B5"; colors["navajowhite"] = "FFDEAD"; colors["navy"] = "000080";
		colors["oldlace"] = "FDF5E6"; colors["olive"] = "808000"; colors["olivedrab"] = "6B8E23"; colors["orange"] = "FFA500";
		colors["orangered"] = "FF4500"; colors["orchid"] = "DA70D6"; colors["palegoldenrod"] = "EEE8AA"; colors["palegreen"] = "98FB98";
		colors["paleturquoise"] = "AFEEEE"; colors["palevioletred"] = "DB7093"; colors["papayawhip"] = "FFEFD5"; colors["peachpuff"] = "FFDAB9";
		colors["peru"] = "CD853F"; colors["pink"] = "FFC0CB"; colors["plum"] = "DDA0DD"; colors["powderblue"] = "B0E0E6";
		colors["purple"] = "800080"; colors["red"] = "FF0000"; colors["rosybrown"] = "BC8F8F"; colors["royalblue"] = "4169E1";
		colors["saddlebrown"] = "8B4513"; colors["salmon"] = "FA8072"; colors["sandybrown"] = "F4A460"; colors["seagreen"] = "2E8B57";
		colors["seashell"] = "FFF5EE"; colors["sienna"] = "A0522D"; colors["silver"] = "C0C0C0"; colors["skyblue"] = "87CEEB";
		colors["slateblue"] = "6A5ACD"; colors["slategray"] = "708090"; colors["snow"] = "FFFAFA"; colors["springgreen"] = "00FF7F";
		colors["steelblue"] = "4682B4"; colors["tan"] = "D2B48C"; colors["teal"] = "008080"; colors["thistle"] = "D8BFD8";
		colors["tomato"] = "FF6347"; colors["turquoise"] = "40E0D0"; colors["violet"] = "EE82EE"; colors["wheat"] = "F5DEB3";
		colors["white"] = "FFFFFF"; colors["whitesmoke"] = "F5F5F5"; colors["yellow"] = "FFFF00"; colors["yellowgreen"] = "9ACD32";
		return colors;
	}

	function OppositeColor(value) {
		value = toHexColor(value).split("#").join('').split('');
		var hexVals = "0123456789ABCDEF";
		var revHexs = hexVals.split('').reverse().join('');
		var currentPos;
		for(var i = 0;i < value.length;i++) {
			currentPos = hexVals.indexOf(value[i]);
			value[i] = revHexs.substring(currentPos,currentPos+1);
		}
		
		return "#" + value.join('');
	}

	function ColorDecToHex(r,g,b) {
		r = r.toString(16); if (r.length == 1) r = '0' + r;
		g = g.toString(16); if (g.length == 1) g = '0' + g; 
		b = b.toString(16); if (b.length == 1) b = '0' + b;
		return "#" + r + g + b;
	}

	function ColorHexToDec(value) {
		var res = [];
		value = value.replace("#", "");
		for(var i = 0;i < 3;i++) {
			res[res.length] = parseInt(value.substr(i * 2, 2), 16);
		}
		return res.join(', ');
	}

	function toHexColor(value) {
		value = udf(value) ? "#FFFFFF" : value;
		if(value.indexOf("rgb(") > -1) {
			value = value.replace("rgb(","").replace(")", "");
			value = eval('ColorDecToHex(' + value + ')'); 
		} else {
			if(value.indexOf("#") > -1) {
				value = value.replace("#", "");
				if(value.length == 3) {
					var s = value.split('');
					$.each(s, function(i) {
						s[i] = s[i] + s[i];
					});
					value = s.join('');
				}
			} else {
				var colName = getHexColorByName(value);
				value = !udf(colName) ? colName : "000000";
			}
		}
		
		return "#" + value.toUpperCase().split('#').join('');
	}

	function checkParentColor(elm, param) {
		/*White is chosen as default to eliminate issues between IE and FF*/
		var pColr = "#ffffff";
		
		$(elm).parents().each(function(){
			var result = $(this).css(param);
			if(result != 'transparent') {
				pColr = result;
				return false;
			}
		});
		
		return pColr;
	}

	function rndColor() {
		var res = [];
		var cm;
		for(var i = 0;i < 3;i++) {
			cm = randRange(0, 255).toString(16); 
			if (cm.length == 1) cm = '0' + cm;
			res[res.length] = cm;
		}
		return "#" + res.join('');
	}

	function randRange(lowVal, highVal) {
		 return Math.floor(Math.random()*(highVal-lowVal+1))+lowVal;
	}

	function udf(val) {
		return typeof(val) == 'undefined' ? true : false;
	}
})(jQuery);
