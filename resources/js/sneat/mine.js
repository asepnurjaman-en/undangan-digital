"use strict";

function imageEl(title, file, url, mode = 'single') {
	var imageElement = `<div class="item-image">`;
		imageElement += `<img src="${url}" alt="${title}">`;
		imageElement += `<div class="overlay">`;
		imageElement += `<button title="button" class="remove unchoose-image">&times;</button>`;
		imageElement += `<h4>${title}</h4>`;
		if (mode=='single') {
			imageElement += `<input type="hidden" name="file" value="${file}">`;
		} else if (mode=='multiple') {
			imageElement += `<input type="hidden" name="files[]" value="${file}">`;
		}
		imageElement += `</div>`;
		imageElement += `</div>`;

	return imageElement;
};


$(document).on('change', '.check-row', function() {
	let id_row = [];
	$(".check-row:checked").each(function(i){
		id_row[i] = $(this).val();
	});
	if ($(this).prop('checked')) {
		$(this).parent().parent().addClass('checked');		
	} else {
		$(this).parent().parent().removeClass('checked');
	}
	if (id_row.length > 0) {
		$(".form-delete input[name=id_delete]").val(id_row);
		$(".form-delete button").prop('disabled', false);
		$(".form-delete button").children('b').text(id_row.length);
	} else {
		$(".form-delete input[name=id_delete]").val(null);
		$(".form-delete button").prop('disabled', true);
		$(".form-delete button").children('b').text(null);
	}
});

$(document).on('change', '.check-image', function() {
	let id_row = [];
	$(".check-image:checked").each(function(i){
		id_row[i] = $(this).val();
	});
	if (id_row.length > 0) {
		$(".choose-images input[name=id_image]").val(id_row);
		$(".choose-images button").prop('disabled', false);
		$(".choose-images button").children('b').text(`(${id_row.length})`);
	} else {
		$(".choose-images input[name=id_image]").val(null);
		$(".choose-images button").prop('disabled', true);
		$(".choose-images button").children('b').text(null);
	}
});

$(document).on('change', '#grouped', function() {
	if ($(this).prop('checked')==true) {
		$("#grouped-alert").removeClass('d-none');
	} else if ($(this).prop('checked')==false) {
		$("#grouped-alert").addClass('d-none');
	}
});

$(document).on('click', '.unchoose-image', function(e) {
	e.preventDefault();
	$(this).parent().parent().parent().remove();
});

if ($(".btn-back").length > 0) {
	$(".btn-back").on('click', function(e) {
		window.history.back();
	});
}

if ($(".change-file-type").length > 0) {
	$(".change-file-type").on('click', function() {
		$("#input-file-type").val($(this).data('file-type'));
	});
}

if ($(".change-input-edited").length > 0) {
	$(".change-input-edited").on('keyup change', function() {
		let brand = $(this).data('edit');
		$(`#edit-${brand}`).prop('checked', true);
	});
}

if ($(".change-input-status").length > 0) {
	$(".change-input-status").on('change', function() {
		let brand = $(this).data('brand'),
			input_group = $(`.input-group-${brand}`),
			input = $(`.input-${brand}`);
		if ($(this).prop('checked') == true) {
			input_group.removeClass('disabled');
			input.prop('readonly', false);
		} else if ($(this).prop('checked') == false) {
			input_group.addClass('disabled');
			input.prop('readonly', true);
		}
	});
}

if ($(".choose-image").length > 0) {
	$(".choose-image").on('change', function() {
		const file = this.files[0];
		if (file){
			let reader = new FileReader();
			reader.onload = function(e){
				$("#thumbail-preview").html(null);
				$("#thumbail-preview").append(`<div>${imageEl('Gambar', 'image', e.target.result)}</div>`);
				//$("#thumbail-preview").children().children('img').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		}
	});
}

if ($(".choose-images").length > 0) {
	$(".choose-images").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			csrf = $("meta[name=csrf-token]").attr('content'),
			id = $(".choose-images input[name=id_image]");
		$.ajax({
			type: 'POST',
			url : action,
			dataType: 'json',
			data: {
				_token: csrf, id: id.val()
			},
			error: function(q,w,e) {
				console.log(q);
				console.log(q.responseText);
			},
			success: function(response) {
				if ($("#single-storage-modal").length > 0) {
					$("#thumbail-preview").html(null);
					$("#single-storage-modal").modal('hide');
					$.each(response, function(i, item) {
						$("#thumbail-preview").append(`<div>${imageEl(item.title, item.file, item.url, 'single')}</div>`);
					});
				} else if ($("#multiple-storage-modal").length > 0) {
					$("#multiple-storage-modal").modal('hide');
					$.each(response, function(i, item) {
						$("#thumbail-preview").append(`<div class="col-6 col-md-3">${imageEl(item.title, item.file, item.url, 'multiple')}<input type="text" name="files_name[]" class="form-control rounded-0" value="${item.title}"></div>`);
					});
				}
				$(".check-image").prop('checked', false);
				setTimeout(() => {
					id.val(null);
					$(".choose-images button").children('b').text(null);
				}, 200);
			}
		});
	});
}

if ($(".choose-youtube").length > 0) {
	$(".choose-youtube").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			csrf = $("meta[name=csrf-token]").attr('content'),
			id = $(".choose-youtube input[name=url-input-youtube]");
		$.ajax({
			type: 'POST',
			url : action,
			dataType: 'json',
			data: {
				_token: csrf, id: id.val()
			},
			error: function(q,w,e) {
				console.log(q);
				console.log(q.responseText);
			},
			success: function(response) {
				console.log(response);
				$("#input-youtube-modal").modal('hide');
				$("#thumbail-preview").html(null);
				$.each(response, function(i, item) {
					$("#thumbail-preview").append(`<div>${imageEl(item.title, item.file, item.url)}</div>`);
				});
			}
		});
	});
}

if ($(".counting-input").length > 0) {
	$(".counting-input").on('keyup', function() {
		$(".counting").text($(this).val().length);
	});
}


if ($(".dropify").length > 0) {
	$(".dropify").dropify({
		messages: {
			'default': '',
			'replace': '',
			'remove':  '&times;',
		}
	});

	$(".dropify").on('change', function(e) {
		$(".dropify-title").val(e.target.files[0].name)
	});
}


if ($(".paste-button").length > 0) {
	$(".paste-button").on('click', function(e) {
		e.preventDefault();
		navigator.clipboard.readText().then(
			cliptext => ($(".paste-input").val(cliptext)),
			err => console.log(err)
		);
	});
}

if ($(".select2").length > 0) {
	function formatState (state) {
		if (!state.id) { return state.text; }
		var $state = $('<span><i class="bx bx-album"></i> ' + state.text +     '</span>');
	   	return $state;
	};

	$(".select2").select2({
		templateResult: formatState
	});
}

if ($(".tag-input").length > 0) {
	var tag_preview = $("#tag-preview"),
		tag_input = $(".tag-input");
	function add_tag(params) {
		return `<span class="badge bg-primary mt-1 me-1"><i class="bx bx-x text-danger cursor-pointer me-1 tag-remove"></i>${params}<input type="hidden" name="tags[]" value="${params}"></span>`;
	}
	$(".tag-button").on('click', function(e) {
		e.preventDefault();
		tag_preview.append(add_tag(tag_input.val()));
		tag_input.val(null);
	});

	$(document).on('click', '.tag-remove', function(e) {
		e.preventDefault();
		$(this).parent().remove();
	});
}

