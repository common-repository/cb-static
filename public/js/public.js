/**
 * The Public script.
 *
 * @link              https://github.com/demispatti/cb-parallax
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/public/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

(function ($) {
	"use strict";

	/**
	 * requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel
	 http://paulirish.com/2011/requestanimationframe-for-smart-animating/
	 http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
	 MIT license
	 */
	(function () {
		var lastTime = 0;
		var vendors = ['ms', 'moz', 'webkit', 'o'];
		for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
			window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
			window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame']
				|| window[vendors[x] + 'CancelRequestAnimationFrame'];
		}

		if (!window.requestAnimationFrame)
			window.requestAnimationFrame = function (callback, element) {
				var currTime = new Date().getTime();
				var timeToCall = Math.max(0, 16 - (currTime - lastTime));
				var id = window.setTimeout(function () {
						callback(currTime + timeToCall);
					},
					timeToCall);
				lastTime = currTime + timeToCall;
				return id;
			};

		if (!window.cancelAnimationFrame)
			window.cancelAnimationFrame = function (id) {
				clearTimeout(id);
			};
	}());

	var requestAnimationFrame = window.mozRequestAnimationFrame || window.msRequestAnimationFrame || window.requestAnimationFrame;

	function Plugin() {

		this.defaultOptions = {
			imageSrc            : '',
			backgroundColor     : '',
			positionX           : 'center',
			positionY           : 'center',
			backgroundAttachment: 'fixed'
		},
			this.image = {
				src                 : cbStatic.imageSrc != 'undefined' ? cbStatic.imageSrc : this.defaultOptions.imageSrc,
				backgroundColor     : cbStatic.backgroundColor != 'undefined' ? cbStatic.backgroundColor : this.defaultOptions.backgroundColor,
				positionX           : cbStatic.positionX != 'undefined' ? cbStatic.positionX : this.defaultOptions.positionX,
				positionY           : cbStatic.positionY != 'undefined' ? cbStatic.positionY : this.defaultOptions.positionX,
				backgroundAttachment: cbStatic.backgroundAttachment != 'undefined' ? cbStatic.backgroundAttachment : this.defaultOptions.backgroundAttachment,

				width : cbStatic.imageWidth != 'undefined' ? cbStatic.imageWidth : this.defaultOptions.imageWidth,
				height: cbStatic.imageHeight != 'undefined' ? cbStatic.imageHeight : this.defaultOptions.imageHeight
			},
			this.overlay = {
				path   : cbStatic.overlayPath != 'undefined' ? cbStatic.overlayPath : this.defaultOptions.overlayPath,
				image  : cbStatic.overlayImage != 'undefined' ? cbStatic.overlayImage : this.defaultOptions.overlayImage,
				opacity: cbStatic.overlayOpacity != 'undefined' ? cbStatic.overlayOpacity : this.defaultOptions.overlayOpacity,
				color  : cbStatic.overlayColor != 'undefined' ? cbStatic.overlayColor : this.defaultOptions.overlayColor
			},
			this.overlayContainer = document.getElementById('cbp_overlay_container'),
			this.body = document.getElementsByTagName('body'),
			this.html = document.getElementsByTagName('html'),
			this.isResizing = false
	}

	Plugin.prototype = {

		constructor                    : Plugin,
		setOverlay                     : function () {

			if (cbStatic.overlayImage != "none") {
				$('body').prepend('<div id="cbp_overlay"></div>');
				this.overlayContainer = $('#cbp_overlay');
				this.overlayContainer.css({
					'background'      : 'url(' + cbStatic.overlayPath + cbStatic.overlayImage + ')',
					'background-color': '#' + cbStatic.overlayColor,
					'opacity'         : cbStatic.overlayOpacity
				});
			}
		},
		setupImageContainer            : function () {

			$('#cbp_image_container').css({
				'background-size' : cbStatic.imageWidth + 'px' + ' ' + cbStatic.imageHeight + 'px',
				'background-color': '#' + cbStatic.backgroundColor
			});
		},
		revertBodyStyling              : function () {

			var body = $('body');
			body.removeClass('custom-background');
			body.removeProp('background-image');
		},
		getHorizontalAlignInPx         : function () {

			var posX = null;
			switch (cbStatic.positionX) {

				case 'left':
					posX = '0';
					break;

				case 'center':
					posX = ($(window).width() / 2) - (cbStatic.imageWidth / 2) + 'px';
					break;

				case 'right':
					posX = $(window).width() - cbStatic.imageWidth + 'px';
					break;
			}
			return posX;
		},
		getVerticalAlignInPx           : function () {

			var posY = null;
			switch (cbStatic.positionY) {

				case 'top':
					posY = '0';
					break;

				case 'center':
					posY = ($(window).height() / 2) - (cbStatic.imageHeight / 2) + 'px';
					break;

				case 'bottom':
					posY = $(window).height() - cbStatic.imageHeight + 'px';
					break;
			}
			return posY;
		},
		staticSetImagePosition         : function () {

			$('#cbp_image_container').css({
				'left': Plugin.prototype.getHorizontalAlignInPx(),
				'top' : Plugin.prototype.getVerticalAlignInPx()
			});
		},
		staticKeepImageAligned         : function () {

			if (Plugin.prototype.isResizing) {
				Plugin.prototype.staticSetImagePosition();
			}
			Plugin.prototype.isResizing = false;
			requestAnimationFrame(Plugin.prototype.staticKeepImageAligned);
		},
		bootstrap                      : function () {

			/*if ($('#ascrail2000').length == 0) {
				this.parallaxPreserveScrolling();
			}*/
			//this.setOverlay();
			this.revertBodyStyling();
			this.setupImageContainer();
			this.staticSetImagePosition();
		},
		prependCanvas                  : function () {

			var element = '<canvas id="cbp_image_container" class="custom-background" width="' + cbStatic.imageWidth + '" height="' + cbStatic.imageHeight + '"></canvas>';
			$('body').prepend(element);
		},
		init                           : function () {

			window.onload = function () {

				var canvas = document.getElementById('cbp_image_container');
				var context = canvas.getContext('2d');
				var img = new Image();

				img.onload = function () {
					context.drawImage(img, 0, 0, cbStatic.imageWidth, cbStatic.imageHeight);
					Plugin.prototype.bootstrap();
				};

				img.src = cbStatic.imageSrc;
			};
		},
		observeResizeEvent             : function () {

			$(window).bind('resize', function () {
				Plugin.prototype.isResizing = true;
				requestAnimationFrame(Plugin.prototype.staticKeepImageAligned);
			});
		}
	};

	$(document).ready(function () {

		var plugin = new Plugin();
		plugin.prependCanvas();
		plugin.init();
		plugin.observeResizeEvent();
	});

})(jQuery);
