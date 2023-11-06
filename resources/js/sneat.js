import * as bootstrap from 'bootstrap';
import PerfectScrollbar from 'perfect-scrollbar';
import Swal from 'sweetalert2';

import './sneat/helpers';
import './sneat/main';
import './sneat/mine';

// swal
const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 5000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	}
});
const SwalDelete = Swal.mixin({
	title: 'Buang data?',
	icon: 'question',
	showCancelButton: true,
	confirmButtonText: 'Ya, buang!',
	cancelButtonText: 'Batal',
	reverseButtons: true,
	customClass: {
		confirmButton: 'btn btn-danger mx-1',
		cancelButton: 'btn btn-light mx-1'
	},
	buttonsStyling: false,
	allowOutsideClick: false,
});

const ps = new PerfectScrollbar(".menu-inner");
if ($(".horizonbar").length > 1) {
	new PerfectScrollbar(".horizonbar");
}

// functions
if ($(".dataTables-origin").length > 0) {
	var dataTables = $(".dataTables-origin").DataTable({
		responsive: true,
		ordering: true,
		autoWidth: false,
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Cari",
			searchClass: "form-control",
			lengthMenu: "_MENU_",
			zeroRecords: "Kosong",
			info: "Data total: _TOTAL_",
			infoEmpty: "",
			paginate: {
				previous: "<i class=\"bx bx-chevron-left\"></i>",
				next: "<i class=\"bx bx-chevron-right\"></i>",
			},
			infoFiltered: "/ _MAX_"
		},
	});
	$(".dataTables_filter").children('label').addClass('d-block p-3');
	$(".dataTables_filter").children('label').children('input').addClass('form-control m-0');
	$(".dataTables_length").children('label').addClass('d-block p-3');
	$(".dataTables_length").children('label').children('select').addClass('form-select m-0');
	$(".dataTables_info").addClass('p-3');
	$(".dataTables_paginate").addClass('p-3');
}

if ($(".dataTables").length > 0) {
	let url = $(".dataTables").data('list'),
		csrf = $("meta[name=csrf-token]").attr('content');
	var dataTables = $(".dataTables").DataTable({
		responsive: true,
		ordering: false,
		autoWidth: false,
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Cari",
			searchClass: "form-control",
			lengthMenu: "_MENU_",
			zeroRecords: "Kosong",
			info: "Data total: _TOTAL_",
			infoEmpty: "",
			paginate: {
				previous: "<i class=\"bx bx-chevron-left\"></i>",
				next: "<i class=\"bx bx-chevron-right\"></i>",
			},
			infoFiltered: "/ _MAX_"
		},
		serverSide: true,
		ajax: {
			url: url,
			type: 'post',
			dataType: 'json',
			data: {
				_token: csrf
			},
			error: function(q,w,e) {
				// console.log(q);
				console.log(q.responseText);
			}
		},
		columns: [
			{ data: "id", name: "id" },
			{ data: "title", name: "title" },
			{ data: "info", name: "info" },
			{ data: "log", name: "log" },
		],
	});
	$(".dataTables_filter").children('label').addClass('d-block p-3');
	$(".dataTables_filter").children('label').children('input').addClass('form-control m-0');
	$(".dataTables_length").children('label').addClass('d-block p-3');
	$(".dataTables_length").children('label').children('select').addClass('form-select m-0');
	$(".dataTables_info").addClass('p-3');
	$(".dataTables_paginate").addClass('p-3');
}

if ($(".form-insert").length > 0) {
	document.addEventListener('keydown', function(event) {
		if (event.ctrlKey && event.key === 's') {
			event.preventDefault();
			$(".form-insert").submit();
		}
	});

	$(".form-insert").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action');
		$.ajax({
			type: 'POST',
			url : action,
			dataType: 'json',
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			error: function(q,w,e) {
				console.log(q);
				console.log(q.responseText);
				$(".form-insert").find("button[type=submit]").prop('disabled', false);
				let message = "<ol style=padding:10px>";
				$.each(q.responseJSON.errors, function(index, value) {
					message += `<li>${value}</li>`;
				});
				message += "</ol>";
				Swal.fire({icon:'error', title:'Error', html:message});
			},
			beforeSend: function() {
				$(".form-insert").find("button[type=submit]").prop('disabled', true);
				Toast.fire({
					icon : 'info',
					title: 'Mohon tunggu',
					text : 'Sedang dalam proses..',
					timer: false
				});
			},
			success: function(response) {
				$(".form-insert").find("button[type=submit]").prop('disabled', false);
				Toast.fire(response.toast);
				if (response.redirect.type=='assign') {
					setTimeout(() => {
						window.location.assign(response.redirect.value);
					}, 1000);
				} else if (response.redirect.type=='dataTables') {
					dataTables.ajax.reload();
					$(".form-insert")[0].reset();
					$('.dropify-clear').click();
				} else if (response.redirect.type=='reload') {
					setTimeout(() => {
						window.location.reload();
					}, 1000);
				} else if (response.redirect.type=='nothing') {
				}
			}
		});
	});
}

if ($(".form-update").length > 0) {
	// $(window).bind('keyup', 'ctrl+s', function(e) {
	// 	if (e.ctrlKey && (e.which == 83)) {
	// 		e.preventDefault();
	// 		$(".form-update").submit();
	// 	}
	// }, false);

	document.addEventListener('keydown', function(event) {
		if (event.ctrlKey && event.key === 's') {
			event.preventDefault();
			$(".form-update").submit();
		}
	});

	$(".form-update").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action');
		$.ajax({
			type: 'POST',
			url : action,
			dataType: 'json',
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			error: function(q,w,e) {
				console.log(q);
				console.log(q.responseText);
				$(".form-update").find("button[type=submit]").prop('disabled', false);
				let message = "<ol style=padding:10px>";
				$.each(q.responseJSON.errors, function(index, value) {
					message += `<li>${value}</li>`;
				});
				message += "</ol>";
				Swal.fire({icon:'error', title:'Error', html:message});
			},
			beforeSend: function() {
				$(".form-update").find("button[type=submit]").prop('disabled', true);
				Toast.fire({
					icon : 'info',
					title: 'Mohon tunggu',
					text : 'Sedang dalam proses..',
					timer: false
				});
			},
			success: function(response) {
				$(".form-update").find("button[type=submit]").prop('disabled', false);
				Toast.fire(response.toast);
				if (response.redirect.type=='assign') {
					setTimeout(() => {
						window.location.assign(response.redirect.value);
					}, 1000);
				} else if (response.redirect.type=='dataTables') {
					dataTables.ajax.reload();
					$(".form-update")[0].reset();
					$('.dropify-clear').click();
				} else if (response.redirect.type=='reload') {
					setTimeout(() => {
						window.location.reload();
					}, 1000);
				} else if (response.redirect.type=='nothing') {
				}
			}
		});
	});
}

if ($(".form-delete").length > 0) {
	$(window).bind('keyup', 'delete', function(e) {
		if (e.which == 46) {
			e.preventDefault();
			$(".form-delete").submit();
		}
	}, false);
	
	$(".form-delete").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			csrf = $("meta[name=csrf-token]").attr('content'),
			id = $("input[name=id_delete]").val(),
			message = $(".form-delete").data('message');
		SwalDelete.fire({
			text: message,
		}).then((response) => {
			if (response.isConfirmed) {
				$.ajax({
					type: 'DELETE',
					url : action,
					dataType: 'json',
					data: {
						_token: csrf, id: id
					},
					error: function(q,w,e) {
						console.log(q);
						console.log(q.responseText);
					},
					success: function(response) {
						Toast.fire(response.toast);
						if (response.redirect.type=='assign') {
							setTimeout(() => {
								window.location.assign(response.redirect.value);
							}, 1000);
						} else if (response.redirect.type=='dataTables') {
							dataTables.ajax.reload();
							$(".form-delete input[name=id_delete]").val(null);
							$(".form-delete button").prop('disabled', true);
							$(".form-delete button").children('b').html(null);
						} else if (response.redirect.type=='reload') {
							setTimeout(() => {
								window.location.reload();
							}, 1000);
						} else if (response.redirect.type=='nothing') {
						}
					}
				});
			}
		});
	});
}


if ($(".delete-member").length > 0) {
	$(".delete-member").on('click', function(e) {
		e.preventDefault();
		let action = $(this).data('url'),
			message = $(this).data('message');
		SwalDelete.fire({
			title: 'Hapus akun?',
			confirmButtonText: 'Hapus akun',
			html: `<p class="p-3">${message}</p>`,
		}).then((response) => {
			if (response.isConfirmed) {
				$.ajax({
					type: 'get',
					url : action,
					success: function(res) {
						Swal.fire({
							html: '<p class="p-3">Proses menghapus data, harap tunggu..</p>',
							timer: 15000,
							allowOutsideClick: false,
							timerProgressBar: true,
							showConfirmButton: false
						}).then((result) => {
							location.assign(res.redirect);
						});
					}
				});
				
			}
		});
	});
}
if ($(".deactive-member").length > 0) {
	$(".active-member").on('click', function(e) {
		e.preventDefault();
		let action = $(this).data('url');
		$.ajax({
			url : action,
			type: 'get',
			error: function(w) {
				console.log(w);
			},
			beforeSend: function() {
				$(".active-member").prop('disabled', true);
				$(".deactive-member").prop('disabled', false);
			},
			success: function(response) {
				$(".deactive-member").show();
				$(".active-member").hide();
				Toast.fire(response.toast);
				$(".user-stat").find('span.badge').text('Aktif').addClass('bg-success').removeClass('bg-gray');
			}
		});
	});
	$(".deactive-member").on('click', function(e) {
		e.preventDefault();
		let action = $(this).data('url');
		$.ajax({
			url : action,
			type: 'get',
			beforeSend: function() {
				$(".deactive-member").prop('disabled', true);
				$(".active-member").prop('disabled', false);
			},
			success: function(response) {
				$(".active-member").show();
				$(".deactive-member").hide();
				Toast.fire(response.toast);
				$(".user-stat").find('span.badge').text('Non-Aktif').removeClass('bg-success').addClass('bg-gray');
			}
		});
	});
}


if ($(".mce").length > 0) {
	tinymce.init({// for write post only
		selector: 'textarea.mce',
		plugins: 'preview paste searchreplace autolink directionality code visualblocks visualchars image link table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount textpattern noneditable charmap quickbars emoticons',
		imagetools_cors_hosts: ['picsum.photos'],
		menubar: false,
		toolbar: [
			'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent',
			'fontselect fontsizeselect formatselect',
			'forecolor backcolor removeformat | table | image link | charmap emoticons | pagebreak | code preview |'
		],
		toolbar_sticky: false,
		automatic_uploads: true,
		autosave_ask_before_unload: true,
		autosave_interval: "30s",
		autosave_prefix: "{path}{query}-{id}-",
		autosave_restore_when_empty: false,
		autosave_retention: "2m",
		content_style: 'img {max-width: 100%;}',
		content_css: '//www.tiny.cloud/css/codepen.min.css',
		file_picker_types: 'image',
		file_picker_callback: function (callback, value, meta) {
			if (meta.filetype === 'file') {
				callback('https://www.google.com/logos/google.jpg', { text: 'Google' });
			}
			if (meta.filetype === 'image') {
				let file_picker_input = document.createElement('input');
				file_picker_input.setAttribute('type', 'file');
				file_picker_input.setAttribute('accept', 'image/*');
				file_picker_input.onchange = function () {
					let file_picker_file = this.files[0];
					let file_picker_reader = new FileReader();
					file_picker_reader.onload = function () {
						let blobId = 'blobid' + (new Date()).getTime();
						let blobCache =  tinymce.activeEditor.editorUpload.blobCache;
						let base64 = file_picker_reader.result.split(',')[1];
						let blobInfo = blobCache.create(blobId, file_picker_file, base64);
						blobCache.add(blobInfo);
						callback(blobInfo.blobUri(), { title: file_picker_file.name });
					};
					file_picker_reader.readAsDataURL(file_picker_file);
				};
				file_picker_input.click();
			}
		},
		height: 360,
		image_dimensions: false,
		image_caption: false,
		image_class: false,
		noneditable_noneditable_class: "mceNonEditable",
		contextmenu: "selectall copy cut paste | link",
		setup: function(editor) {
			editor.on('change', function() {
				editor.save();
			});
			editor.addShortcut("ctrl+s", "Save Form", "custom_ctrl_s");
			editor.addCommand("custom_ctrl_s", function() {
				if ($(".form-insert").length > 0) {
					$(".form-insert").submit();
				} else if ($(".form-update").length > 0) {				
					$(".form-update").submit();
				} else {
					alert('form apa ini?');
				}
			});
		}
	});
}

// Init BS Tooltip
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
return new bootstrap.Tooltip(tooltipTriggerEl);
});