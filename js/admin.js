$(document).ready(function () {
	//	Menú
	$('.section-link').on('click', function () {
		$('.section-link').removeClass('active')
		$(this).addClass('active')
		$('.section-content').addClass('hidden')
		var target = $(this).children().attr('href')
		$(target).removeClass('hidden')
		//	Update category list
		$.get('../api/category_info.php')
		.done(function (data) {
			var data = JSON.parse(data)
			//	Lista de categorías actualizada
			var html
			for (var i in data) {
				html += '<option value="' + data[i].id + '">' + data[i].category + '</option>'
			}
			$('#inputCategory').html(html)
			$('#deleteImageCategory').html(html)
			$('#oldCategory').html(html)
			$('#deletedCategory').html(html)
		})
		//	Reset inputs
		$('input').val('')
		$('select').val('')
		//	Reset error fields
		$('#createCategoryError').html('')
		$('#createCategoryError').removeClass('alert')
		$('#createCategoryError').removeClass('alert-danger')
		$('#updateCategoryError').html('')
		$('#updateCategoryError').removeClass('alert')
		$('#updateCategoryError').removeClass('alert-danger')
		$('#deleteImageError').html('')
		$('#deleteImageError').removeClass('alert')
		$('#deleteImageError').removeClass('alert-danger')
		$('#deleteCategoryError').html('')
		$('#deleteCategoryError').removeClass('alert')
		$('#deleteCategoryError').removeClass('alert-danger')
	})
	//	Create category
	$('#createCategory').on('click', function () {
		//	Variables from form
		var category = $('#newCategory').val()
		var location = $('#newLocation').val()
		var disabled = $('#newDisabled').val()
		//	Obtain original HTML inside button
		var original_html = $('#createCategory').html()
		//	Disable button & show loader
		$('#createCategory').attr('disabled', 'disabled')
		$('#createCategory').html('<i class="fa fa-spinner fa-spin"></i>')
		//	Remove error alert
		$('#createCategoryError').html('')
		$('#createCategoryError').removeClass('alert')
		$('#createCategoryError').removeClass('alert-danger')
		//	POST
		if(category && location && (disabled == 1 || disabled == 0)) {
			$.post('../api/create_category.php', {
				category : category,
				location : location,
				disabled : disabled
			})
			.done(function (data) {
				//	Reset inputs
				$('input').val('')
				if(data.error) {
					//	Add class alert
					$('#createCategoryError').addClass('alert')
					$('#createCategoryError').addClass('alert-danger')
					$('#createCategoryError').html(data.error)
					//	Enable button & reset HTML
					$('#createCategory').removeAttr('disabled')
					$('#createCategory').html(original_html)
				}
				else {
					//	Add class alert
					$('#createCategoryError').addClass('alert')
					$('#createCategoryError').addClass('alert-success')
					$('#createCategoryError').html('La categoría ' + category + ' se creó correctamente')
					//	Enable button & reset HTML
					$('#createCategory').removeAttr('disabled')
					$('#createCategory').html(original_html)
				}
			})
		}
		else {
			//	Add class alert
			$('#createCategoryError').addClass('alert')
			$('#createCategoryError').addClass('alert-danger')
			$('#createCategoryError').html('Por favor, rellena todos los campos del formulario')
			//	Enable button & reset HTML
			$('#createCategory').removeAttr('disabled')
			$('#createCategory').html(original_html)
		}
	})
	//	Create category
	$('#editCategory').on('click', function () {
		//	Variables from form
		var old_category = $('#oldCategory').val()
		var category = $('#updateCategory').val()
		var location = $('#updateLocation').val()
		var disabled = $('#updateDisabled').val()
		//	Obtain original HTML inside button
		var original_html = $('#updateCategory').html()
		//	Disable button & show loader
		$('#updateCategory').attr('disabled', 'disabled')
		$('#updateCategory').html('<i class="fa fa-spinner fa-spin"></i>')
		//	Remove error alert
		$('#updateCategoryError').html('')
		$('#updateCategoryError').removeClass('alert')
		$('#updateCategoryError').removeClass('alert-danger')
		//	POST
		if(old_category && category && location && (disabled == 1 || disabled == 0)) {
			$.post('../api/update_category.php', {
				old_category : old_category,
				category : category,
				location : location,
				disabled : disabled
			})
			.done(function (data) {
				//	Reset inputs
				$('input').val('')
				$('select').val('')
				if(data.error) {
					//	Add class alert
					$('#updateCategoryError').addClass('alert')
					$('#updateCategoryError').addClass('alert-danger')
					$('#updateCategoryError').html(data.error)
					//	Enable button & reset HTML
					$('#updateCategory').removeAttr('disabled')
					$('#updateCategory').html(original_html)
				}
				else {
					//	Update category list
					$.get('../api/category_info.php')
					.done(function (data1) {
						var data1 = JSON.parse(data1)
						//	Lista de categorías actualizada
						var html
						for (var i in data1) {
							html += '<option value="' + data1[i].id + '">' + data1[i].category + '</option>'
						}
						$('#oldCategory').html(html)
					})
					//	Add class alert
					$('#updateCategoryError').addClass('alert')
					$('#updateCategoryError').addClass('alert-success')
					$('#updateCategoryError').html('La categoría ' + category + ' se actualizó correctamente')
					//	Enable button & reset HTML
					$('#updateCategory').removeAttr('disabled')
					$('#updateCategory').html(original_html)
				}
			})
			.fail(function () {
				//	Add class alert
				$('#updateCategoryError').addClass('alert')
				$('#updateCategoryError').addClass('alert-danger')
				$('#updateCategoryError').html('Ha habido un error procesando la petición')
				//	Enable button & reset HTML
				$('#updateCategory').removeAttr('disabled')
				$('#updateCategory').html(original_html)
			})
		}
		else {
			//	Add class alert
			$('#updateCategoryError').addClass('alert')
			$('#updateCategoryError').addClass('alert-danger')
			$('#updateCategoryError').html('Por favor, rellena todos los campos del formulario')
			//	Enable button & reset HTML
			$('#updateCategory').removeAttr('disabled')
			$('#updateCategory').html(original_html)
		}
	})
	//	Create category
	$('#deleteCategory').on('click', function () {
		//	Variables from form
		var category = $('#deletedCategory').val()
		var sure = $('#sureDeleteCategory').prop('checked')
		//	Obtain original HTML inside button
		var original_html = $('#deleteCategory').html()
		//	Disable button & show loader
		$('#deleteCategory').attr('disabled', 'disabled')
		$('#deleteCategory').html('<i class="fa fa-spinner fa-spin"></i>')
		//	Remove error alert
		$('#deleteCategoryError').html('')
		$('#deleteCategoryError').removeClass('alert')
		$('#deleteCategoryError').removeClass('alert-danger')
		//	POST
		if(category && sure) {
			$.post('../api/delete_category.php', {
				category : category
			})
			.done(function (data) {
				//	Reset inputs
				$('input').val('')
				$('select').val('')
				if(data.error) {
					//	Add class alert
					$('#deleteCategoryError').addClass('alert')
					$('#deleteCategoryError').addClass('alert-danger')
					$('#deleteCategoryError').html(data.error)
					//	Enable button & reset HTML
					$('#deleteCategory').removeAttr('disabled')
					$('#deleteCategory').html(original_html)
				}
				else {
					//	Update category list
					$.get('../api/category_info.php')
					.done(function (data1) {
						var data1 = JSON.parse(data1)
						//	Lista de categorías actualizada
						var html
						for (var i in data1) {
							html += '<option value="' + data1[i].id + '">' + data1[i].category + '</option>'
						}
						$('#deletedCategory').html(html)
					})
					//	Add class alert
					$('#deleteCategoryError').addClass('alert')
					$('#deleteCategoryError').addClass('alert-success')
					$('#deleteCategoryError').html('La categoría ' + category + ' se eliminó correctamente')
					//	Enable button & reset HTML
					$('#deleteCategory').removeAttr('disabled')
					$('#deleteCategory').html(original_html)
				}
			})
			.fail(function () {
				//	Add class alert
				$('#deleteCategoryError').addClass('alert')
				$('#deleteCategoryError').addClass('alert-danger')
				$('#deleteCategoryError').html('Ha habido un error procesando la petición')
				//	Enable button & reset HTML
				$('#deleteCategory').removeAttr('disabled')
				$('#deleteCategory').html(original_html)
			})
		}
		else {
			//	Add class alert
			$('#deleteCategoryError').addClass('alert')
			$('#deleteCategoryError').addClass('alert-danger')
			$('#deleteCategoryError').html('Por favor, selecciona una categoría y marca la casilla')
			//	Enable button & reset HTML
			$('#deleteCategory').removeAttr('disabled')
			$('#deleteCategory').html(original_html)
		}
	})
	//	Create category
	$('#deleteImage').on('click', function () {
		//	Variables from form
		var category = $('#deleteImageCategory').val()
		var location = $('#deleteImageName').val()
		var sure = $('#sureDeleteImage').prop('checked')
		//	Obtain original HTML inside button
		var original_html = $('#deleteImage').html()
		//	Disable button & show loader
		$('#deleteImage').attr('disabled', 'disabled')
		$('#deleteImage').html('<i class="fa fa-spinner fa-spin"></i>')
		//	Remove error alert
		$('#deleteImageError').html('')
		$('#deleteImageError').removeClass('alert')
		$('#deleteImageError').removeClass('alert-danger')
		//	POST
		if(location && category && sure) {
			$.post('../api/delete_image.php', {
				location : location
			})
			.done(function (data) {
				//	Reset inputs
				$('input').val('')
				$('select').val('')
				if(data.error) {
					//	Add class alert
					$('#deleteImageError').addClass('alert')
					$('#deleteImageError').addClass('alert-danger')
					$('#deleteImageError').html(data.error)
					//	Enable button & reset HTML
					$('#deleteImage').removeAttr('disabled')
					$('#deleteImage').html(original_html)
				}
				else {
					$('#deleteImageShow').html('')
					//	Add class alert
					$('#deleteImageError').addClass('alert')
					$('#deleteImageError').addClass('alert-success')
					$('#deleteImageError').html('La imagen ' + location + ' se eliminó correctamente')
					//	Enable button & reset HTML
					$('#deleteImage').removeAttr('disabled')
					$('#deleteImage').html(original_html)
				}
			})
			.fail(function () {
				//	Add class alert
				$('#deleteImageError').addClass('alert')
				$('#deleteImageError').addClass('alert-danger')
				$('#deleteImageError').html('Ha habido un error procesando la petición')
				//	Enable button & reset HTML
				$('#deleteImage').removeAttr('disabled')
				$('#deleteImage').html(original_html)
			})
		}
		else {
			//	Add class alert
			$('#deleteImageError').addClass('alert')
			$('#deleteImageError').addClass('alert-danger')
			$('#deleteImageError').html('Por favor, selecciona una imagen y marca la casilla')
			//	Enable button & reset HTML
			$('#deleteImage').removeAttr('disabled')
			$('#deleteImage').html(original_html)
		}
	})
	//	Retrieve info from a specific category
	$('#oldCategory').on('change', function () {
		var id = $('#oldCategory').val()
		$.get('../api/category_info.php?id=' + id)
		.done(function (data) {
			var data = JSON.parse(data)
			data = data[0]
			$('#updateCategory').val(data.category)
			$('#updateLocation').val(data.location)
			$('#updateDisabled').val(data.disabled)
		})
	})
	//	Retrieve info from a specific category
	$('#oldCategory').on('click', function () {
		var id = $('#oldCategory').val()
		$.get('../api/category_info.php?id=' + id)
		.done(function (data) {
			var data = JSON.parse(data)
			data = data[0]
			$('#updateCategory').val(data.category)
			$('#updateLocation').val(data.location)
			$('#updateDisabled').val(data.disabled)
		})
	})
	//	Get folder content
	$('#section3-link').on('click', function () {
		var id = $('#deleteImageCategory').val()
		$.get('../api/category_content.php?id=' + id)
		.done(function (data) {
			var data = JSON.parse(data)
			var html = ''
			for(var i in data) {
				html += '<option value="' + data[i] + '">' + data[i].split('/')[3] + '</option>'
			}
			$('#deleteImageName').html(html)
			$('#deleteImageShow').html(data.length ? '<img src="../' + data[0] + '">' : '')
		})
	})
	//	Get folder content
	$('#deleteImageCategory').on('change', function () {
		var id = $('#deleteImageCategory').val()
		$.get('../api/category_content.php?id=' + id)
		.done(function (data) {
			var data = JSON.parse(data)
			var html = ''
			for(var i in data) {
				html += '<option value="' + data[i] + '">' + data[i].split('/')[3] + '</option>'
			}
			$('#deleteImageName').html(html)
			$('#deleteImageShow').html(data.length ? '<img src="../' + data[0] + '">' : '')
		})
	})
	//	Get folder content
	$('#deleteImageCategory').on('click', function () {
		var id = $('#deleteImageCategory').val()
		$.get('../api/category_content.php?id=' + id)
		.done(function (data) {
			var data = JSON.parse(data)
			var html = ''
			for(var i in data) {
				html += '<option value="' + data[i] + '">' + data[i].split('/')[3] + '</option>'
			}
			$('#deleteImageName').html(html)
			$('#deleteImageShow').html(data.length ? '<img src="../' + data[0] + '">' : '')
		})
	})
	//	Show loaded image
	$('#deleteImageName').on('click', function () {
		var location = $('#deleteImageName').val()
		$('#deleteImageShow').html(location ? '<img src="../' + location + '">' : '')
	})
	//	Retrieve info from a specific category
	$('#section1-link').on('click', function () {
		$.get('../api/logs_info.php?limit=' + 8)
		.done(function (data) {

			var data = JSON.parse(data)
			console.log(data)
			var html = ''

			for(var i in data) {

				var partes = data[i].createdAt.split(' ')
				fecha = partes[0]
				hora = partes[1]

				html += '<div class="actividad-item">' +
							'<div class="actividad-image">' +
								'<img src="../img/users/' + data[i].idUser + '.png"></img>' +
							'</div>' +
							'<div class="actividad-text">' +
								'<div class="actividad-item-title">' + data[i].description + '</div>' +
								'<div class="actividad-item-desc"><i class="fa fa-clock-o"></i> ' + fecha + ' a las ' + hora + '</div>' +
							'</div>' +
						'</div>'
			}
			$('.actividad').html(html)
		})
	})
})