(function (doc, win) {
				var docEl = doc.documentElement,
				resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
				recalc = function () {
					var clientWidth = docEl.clientWidth;
					if (!clientWidth) return;
					docEl.style.fontSize = 100 * (clientWidth / 360) + 'px';
					console.log(docEl.style.fontSize);
				};
				
				if (!doc.addEventListener) return;
				win.addEventListener(resizeEvt, recalc, false);
				doc.addEventListener('DOMContentLoaded', recalc, false);
				
			})(document, window);
//<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">