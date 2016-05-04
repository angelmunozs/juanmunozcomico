$(document).ready(function () {
	var count = $("img").length
	var percent = (100 / ($("img").length))
	var global = 0

	$('img').each(function () {
		$(this).load(function () {
			$('#loader-message').html(Math.round(global) + '%')
			global = global + percent
		})
	})

	$('a.modal-galeria-link').click(function () {

		var url = $(this).attr('data-src')
		var title = $(this).attr('data-title')

		$("#modal-generico").on('hide.bs.modal', function() {
			$("#modal-generico-title").html('')
			$("#modal-generico-body").html('')
		})
		
		$("#modal-generico").on('show.bs.modal', function() {
			//	Cargar primero el título
			$("#modal-generico-title").html(title)
			//	Cargar el contenido
			$("#modal-generico-body").html('<div id="modal-loader"><i class="fa fa-spin fa-spinner"></i></div><img src="' + url + '" alt="' + title + '">')
			//	Cuando esté listo, quitar icono spinner
			$("#modal-generico-body img").on('load', function () {
				$('#modal-loader').hide()
			})
		})
	})

	$('a.modal-video-link').click(function () {

		var url = $(this).attr('data-src')
		var title = $(this).attr('data-title')

		$("#modal-generico").on('hide.bs.modal', function() {
			$("#modal-generico-title").html('')
			$("#modal-generico-body").html('')
		})
		
		$("#modal-generico").on('show.bs.modal', function() {
			//	Cargar primero el título
			$("#modal-generico-title").html(title)
			//	Cargar el contenido
			$("#modal-generico-body").html('<div id="modal-loader"><i class="fa fa-spin fa-spinner"></i></div><iframe id="modal-video-iframe" src="' + url + '" frameborder="0" allowfullscreen></iframe>')
			//	Cuando esté listo, quitar icono spinner
			$("#modal-generico-body iframe").on('load', function () {
				$('#modal-loader').hide()
			})
		})
	})
})

$(window).load(function () {

	//	Contenido principal
	$('#loader').fadeOut()
	$('#contenido-principal').fadeIn()

	//	Get main nav height
	var navHeight = parseInt($('#nav').css('height'))
	
	//	Sticky nav
	$("#nav").sticky({
		topSpacing: 0
	})

	//	Mínima altura del opening
	$('#opening').css('height', $(window).height())
	$(window).on('resize', function() {
		$('#opening').css('height', $(window).height())
	})

	//	Positions of page sections
	var positions = [
		{
			section : 'principal',
			offset : $('#section-principal').offset().top - navHeight - 1
		},
		{
			section : 'biografia',
			offset : $('#section-biografia').offset().top - navHeight - 1
		},
		{
			section : 'curriculum',
			offset : $('#section-curriculum').offset().top - navHeight - 1
		},
		{
			section : 'galeria',
			offset : $('#section-galeria').offset().top - navHeight - 1
		},
		{
			section : 'espectaculo',
			offset : $('#section-espectaculo').offset().top - navHeight - 1
		},
		{
			section : '',
			offset : $(document).height()
		}
	]

	//	Cambiar posición
	var changeSection = function(offset) {
		//	Posición actual
		var actualPos = $(document).scrollTop()
		if(actualPos < positions[0].offset) {
			$(".navbar-nav li.active").removeClass("active")
		}
		//	Analizar intervalos
		for(var i = 0; i < positions.length - 1; i++) {
			if(actualPos >= positions[i].offset && actualPos < positions[i + 1].offset) {
				$(".navbar-nav li.active").removeClass("active")
				$('#section-' + positions[i].section + '-li').addClass('active')
				window.location.hash = '#' + positions[i].section
				return true
			}
		}
	}

	//	Actualización de link pulsado conforme se desciende por la página
	$(document).on('scroll', function() {
		return changeSection(navHeight)
	})

	//	Al hace click en la flecha del opening
	$('#start').click(function (e) {
		//	Prevent default behavior
		e.preventDefault()
		//	Deactivate scrolling
		$(document).off("scroll")
		//	Update element with class 'active'
		$(this).parent().addClass('active')
		//	Locate target
		var target = $(this).attr('href')
		var target_clean = target.split('-')[1]
		//	Animate scrolling
		$('html, body').stop().animate({
			'scrollTop': $(target).offset().top - navHeight
		}, 300, 'swing', function() {
			//	Update hash
			window.location.hash = target_clean
			//	Reactivate scrolling
			$(document).on('scroll', function() {
				return changeSection(navHeight)
			})
		})
	})

	//	Menu links
	$('.menu-link').click(function (e) {
		//	Prevent default behavior
		e.preventDefault()
		//	Deactivate scrolling
		$(document).off("scroll")
		//	Update element with class 'active'
		$(".navbar-nav li.active").removeClass("active")
		$(this).parent().addClass('active')
		//	Locate target
		var target = $(this).attr('href')
		var target_clean = target.split('-')[1]
		//	Animate scrolling
		$('html, body').stop().animate({
			'scrollTop': $(target).offset().top - navHeight
		}, 300, 'swing', function() {
			//	Update hash
			window.location.hash = target_clean
			//	Reactivate scrolling
			$(document).on('scroll', function() {
				return changeSection(navHeight)
			})
		})
	})
})