import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';
import html2canvas from 'html2canvas';
import CircleProgress from 'js-circle-progress';

// swal
const Toast = Swal.mixin({
	toast: true,
	position: 'bottom',
	showConfirmButton: false,
	timer: 5000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	}
});

if ($(".copy-text").length > 0) {
	$(".copy-text").on('click', function(e) {
		e.preventDefault();
		var text = $(this).data('text');
		$("body").append('<textarea name="selected-text"></textarea>');
		$("textarea[name=selected-text]").css('position', 'absolute').css('transform', 'scale(0,0)').val(text).select();
		if (document.execCommand('copy')) {
			new Toast({icon:'info',title:'Disalin',text:'Disalin ke papan klip.'});
			$("textarea[name=selected-text]").remove();
		}
	});
}

// Progress
if ($(".progress").length > 0) {
	var set_max = $(".progress").data('max'),
		set_val = $(".progress").data('value');
	const cp = new CircleProgress('.progress', {
		value: set_val,
		max: set_max
	});
}

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

if ($(".dataTables").length > 0) {
	let action = $(".dataTables").data('list'),
		csrf = $("meta[name=csrf-token]").attr('content');
	var dataTables = $(".dataTables").DataTable({
		responsive: true,
		ordering: false,
		lengthChange: false,
		lengthMenu: false,
		autoWidth: false,
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Cari",
			searchClass: "form-control",
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
			url: action,
			type: 'post',
			dataType: 'json',
			data: {
				_token: csrf
			},
			error: function(q,w,e) {
				console.log(q, w, e);
			}
		},
		columns: [
			{ data: "image", name: "image" },
			{ data: "title", name: "title" },
			{ data: "info", name: "info" },
		],
	});
	$(".dataTables_filter").css('float', 'unset');
	$(".dataTables_filter").children('label').addClass('d-block pb-1');
	$(".dataTables_filter").children('label').children('input').addClass('form-control form-control-sm m-0');
	$(".dataTables_info").addClass('small');
	$(".dataTables_paginate").addClass('small');
}

if ($(".btn_upload").length > 0) {
	var btnUpload = $(".btn_upload").children('input[type=file'),
		btnOuter = $(".button_outer");
	btnUpload.on('change', function(e){
		var ext = btnUpload.val().split('.').pop().toLowerCase();
		if ($.inArray(ext, ['png','jpg','jpeg']) == -1) {
			$(".error_msg").text(null);
		} else {
			$(".error_msg").text(null);
			btnOuter.addClass('file_uploading');
			setTimeout(function(){
				btnOuter.addClass('file_uploaded');
			}, 2900);
			var uploadedFile = URL.createObjectURL(e.target.files[0]);
			setTimeout(function(){
				$("#uploaded_view").append('<img src="'+uploadedFile+'" />').addClass('show');
			}, 3000);
		}
	});
	$(".file_remove").on('click', function(e) {
		$("#uploaded_view").removeClass('show');
		$("#uploaded_view").find('img').remove();
		btnOuter.removeClass('file_uploading');
		btnOuter.removeClass('file_uploaded');
	});
}

if ($(".strbox-store").length > 0) {
	$(".strbox-store").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			submit = $(this).find('button[type=submit]');
		$.ajax({
			type: 'post',
			url : action,
			dataType: 'json',
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			error: function(q,w,e) {
				submit.children('span').text('Coba lagi');
				submit.prop('disabled', false);
				let message = "<ol style=padding:10px>";
				$.each(q.responseJSON.errors, function(index, value) {
					message += `<li>${value}</li>`;
				});
				message += "</ol>";
				console.log(q,w,e);
			},
			beforeSend: function() {
				submit.prop('disabled', true);
				submit.children('span').text('Mengunggah...');
			},
			success: function(response) {
				console.log(response);
				dataTables.ajax.reload();
				$(".strbox-store")[0].reset();
				submit.children('span').text('Unggah');
				submit.prop('disabled', false);
			}
		});
	});

	$(document).on('click', '.unuse-image', function(e) {
		e.preventDefault();
		let target = $(this).data('target');
		$(`#${target}`).remove();
	})
}

$(".delete-event").on('click', function(e) {
	e.preventDefault();
	let action = $(this).data('url'),
		token = $("meta[name=csrf-token]").attr('content');
	Swal.fire({
		icon: 'question',
		title: 'Hapus acara?',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				type: 'post',
				url : action,
				dataType: 'json',
				data: {
					_method: 'DELETE', _token: token
				},
				beforeSend: function() {
					console.log('loading');
				},
				error: function(q,w,e) {
					console.log(q,w,e);
				},
				success: function(response) {
					location.reload();
				}
			})
		}
	});
});

$(".delete-story").on('click', function(e) {
	e.preventDefault();
	let action = $(this).data('url'),
		token = $("meta[name=csrf-token]").attr('content');
	Swal.fire({
		icon: 'question',
		title: 'Hapus cerita?',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				type: 'post',
				url : action,
				dataType: 'json',
				data: {
					_method: 'DELETE', _token: token
				},
				beforeSend: function() {
					console.log('loading');
				},
				error: function(q,w,e) {
					console.log(q,w,e);
				},
				success: function(response) {
					location.reload();
				}
			})
		}
	});
});

$(".delete-souvenir").on('click', function(e) {
	e.preventDefault();
	let action = $(this).data('url'),
		token = $("meta[name=csrf-token]").attr('content');
	Swal.fire({
		icon: 'question',
		title: 'Hapus souvenir?',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				type: 'post',
				url : action,
				dataType: 'json',
				data: {
					_method: 'DELETE', _token: token
				},
				beforeSend: function() {
					console.log('loading');
				},
				error: function(q,w,e) {
					console.log(q,w,e);
				},
				success: function(response) {
					location.reload();
				}
			})
		}
	});
});

if ($(".figure-catcher").length > 0) {
	$(".figure-catcher").on('click', function(e) {
		e.preventDefault();
		var action = $(this).data('url'),
			button = $(".figure-catcher"),
			csrf = $("meta[name=csrf-token]").attr('content');
		html2canvas(document.querySelector(".figure-image")).then(canvas => {
			var imgData = canvas.toDataURL('image/webp');
			$.ajax({
				type: 'post',
				url : action,
				dataType: 'json',
				headers: {
				},
				data: {
					_token: csrf, _method: 'PUT', base64data: imgData
				},
				error: function(q,w,e) {
					button.prop('disabled', false);
					console.log(q,w,e);
				},
				beforeSend: function() {
					button.prop('disabled', true);
				},
				success: function(response) {
					button.prop('disabled', false);
					Toast.fire(response.toast);
				}
			});
		});
	})
}

if ($(".save-menu").length > 0) {
	document.addEventListener('keydown', function(event) {
		if (event.ctrlKey && event.key === 's') {
			event.preventDefault();
			$(".save-menu").submit();
		}
	});

	$(".save-menu").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			submit = $(this).find('button[type=submit]');
		$.ajax({
			type: 'post',
			url : action,
			dataType: 'json',
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			error: function(q,w,e) {
				console.log(q,w,e);
				submit.children('i').removeClass('bx-loader bx-spin').addClass('bx-save');
				submit.children('span').text('Coba Lagi');
				submit.prop('disabled', false);
				$.each(q.responseJSON.errors, function(index, value) {
					$(`var[dir=${index}]`).after(`<sup role="alert" data-bs-toggle="tooltip" data-bs-placement="right" title="${value}">!</sup>`);
				});
				Swal.fire({
					icon: 'warning',
					text: "Lengkapi "
				});
			},
			beforeSend: function() {
				$("sup[role=alert]").remove();
				Toast.fire({
					icon: 'info',
					title: 'Proses dimulai..'
				});
				submit.children('i').addClass('bx-loader bx-spin').removeClass('bx-save');
				submit.prop('disabled', true);
				submit.children('span').text('Memeriksa data...');
			},
			success: function(response) {
				submit.children('i').removeClass('bx-loader bx-spin').addClass('bx-save');
				submit.prop('disabled', false);
				submit.children('span').text('Simpan');
				Toast.fire(response.toast);
				if (response.page=='reload') {
					location.reload();
				}
			}
		});
	});
}