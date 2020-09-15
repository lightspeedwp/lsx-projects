/**
 * Scripts
 *
 * @package lsx-projects
 */

(function($, window, document, undefined) {
	"use strict";

	var initSlider = function() {
			var $projectSlider = $(
				"#lsx-projects-slider, #lsx-products-slider, #lsx-alt-products-slider"
			);
			console.log($projectSlider);

			$projectSlider.on("init", function(event, slick) {
				if (
					slick.options.arrows &&
					slick.slideCount > slick.options.slidesToShow
				)
					$projectSlider.addClass("slick-has-arrows");
			});

			$projectSlider.on("setPosition", function(event, slick) {
				if (!slick.options.arrows)
					$projectSlider.removeClass("slick-has-arrows");
				else if (slick.slideCount > slick.options.slidesToShow)
					$projectSlider.addClass("slick-has-arrows");
			});

			$projectSlider.slick({
				draggable: false,
				infinite: true,
				swipe: false,
				cssEase: "ease-out",
				dots: true,
				slidesToShow: 3,
				slidesToScroll: 3,
				responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2,
							draggable: true,
							arrows: false,
							swipe: true
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							draggable: true,
							arrows: false,
							swipe: true
						}
					}
				]
			});

			$('.single-project a[data-toggle="tab"]').on(
				"shown.bs.tab",
				function(e) {
					$(
						"#lsx-services-slider, #lsx-projects-slider, #lsx-products-slider, #lsx-alt-products-slider, #lsx-testimonials-slider, #lsx-team-slider, .lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider"
					).slick("setPosition");
				}
			);
		},
		initIsotope = function() {
			var $container = $(".lsx-projects-row");

			$container.isotope({
				itemSelector: ".lsx-projects-column",
				layoutMode: "fitRows"
			});

			var $option_sets = $(".lsx-projects-filter"),
				$option_links = $option_sets.find("a");

			$option_links.click(function() {
				var $this = $(this);

				if ($this.parent().hasClass("active")) {
					return false;
				}

				// var $option_sets = $this.parents( '.lsx-projects-filter' );

				$option_sets.find(".active").removeClass("active");
				$this.parent().addClass("active");

				var selector = $(this).attr("data-filter");
				$container.isotope({ filter: selector });

				return false;
			});

			setTimeout(function() {
				$container.isotope();
			}, 300);

			$(document).on("lazybeforeunveil", function() {
				setTimeout(function() {
					$container.isotope();
				}, 300);
			});

			$(window).load(function() {
				$container.isotope();
			});
		},
		fixProjectSidebar = function() {
			var gap = 30;

			$("body.single-project .entry-fixed-sidebar").scrollToFixed({
				marginTop: function() {
					var wpadminbar = $("#wpadminbar"),
						menu = $("#masthead"),
						marginTop = gap;

					if (wpadminbar.length > 0) {
						marginTop += wpadminbar.outerHeight(true);
					}

					if (menu.length > 0) {
						marginTop += menu.outerHeight(true);
					}

					return marginTop;
				},

				limit: function() {
					var limit = $(this).outerHeight(true) + gap;

					if ($(".lsx-projects-section.lsx-full-width").length > 0) {
						limit =
							$(".lsx-projects-section.lsx-full-width")
								.first()
								.offset().top - limit;
					} else if ($(".entry-tabs").length > 0) {
						limit = $(".entry-tabs").offset().top - limit;
					} else if ($("#footer-cta").length > 0) {
						limit = $("#footer-cta").offset().top - limit;
					} else if ($("#footer-widgets").length > 0) {
						limit = $("#footer-widgets").offset().top - limit;
					} else {
						limit = $("footer.content-info").offset().top - limit;
					}

					return limit;
				},

				minWidth: 768,
				removeOffsets: true
			});
		};

	initSlider();
	fixProjectSidebar();

	if ($("body").hasClass("post-type-archive-project")) {
		initIsotope();
	}
})(jQuery, window, document);
