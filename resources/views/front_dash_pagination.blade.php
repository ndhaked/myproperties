@if ($paginator->hasPages())
<div class="flex justify-between items-center p-4 border-t border-gray-100 text-sm text-gray-600">

    {{-- Previous Button --}}
    @if ($paginator->onFirstPage())
        <button 
            class="portal-btn-compact-secondary"
			disabled="true"
            aria-disabled="true"
        >
            Previous
        </button>
    @else
        <button 
            onclick="paginate('{{ $paginator->previousPageUrl() }}', {{ $paginator->currentPage() - 1 }})"
            class="portal-btn-compact-secondary"
        >
            Previous
        </button>
    @endif

    {{-- Page Info --}}
    <div class="text-sm font-medium">
        Page <span class="font-bold">{{ $paginator->currentPage() }}</span> 
        of {{ $paginator->lastPage() }}
    </div>

    {{-- Next Button --}}
    @if ($paginator->hasMorePages())
        <button 
            onclick="paginate('{{ $paginator->nextPageUrl() }}', {{ $paginator->currentPage() + 1 }})"
            class="portal-btn-compact-secondary"
        >
            Next
        </button>
    @else
        <button 
            class="portal-btn-compact-secondary"
            aria-disabled="true"
			disabled="true"
        >
            Next
        </button>
    @endif
</div>
@endif

@section('uniquePageScript')
<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(() => {
			paginate();
		}, 100)
	})

	function paginate(url = '', data, news_type = '', is_loader_stop = '') {
		$("#more_pagination a").removeClass('paginate_active');
		$(data).addClass('paginate_active');
		var nw_type = '';
		var from = '';
		var to = '';

		if ($("input[name='name']").val()) {
			name = $("input[name='name']").val();
		}

		if ($("input[name='from']").val()) {
			from = $("input[name='from']").val();
		}
		if ($("input[name='to']").val()) {
			to = $("input[name='to']").val();
		}

		if ($("input[name='search_type']").val()) {
			type = $("input[name='search_type']").val();
		}
		
		var custom_page_number = 1;
		if (parseInt(data)) {
			custom_page_number = parseInt(data);
		}

		if (url == '') {
			var url = REQUEST_URL;
			url = url + "?search=" + name;
		}

		if (news_type != undefined && nw_type != undefined) {
			url = REQUEST_URL + '?type=' + nw_type + '&page=' + custom_page_number;
		}

		var _changeUrl = url;

		if (type != '') {
			_changeUrl = _changeUrl + "&type=" + type;
		}
		if (from != '') {
			_changeUrl = _changeUrl + "&from=" + from;
		}
		if (to != '') {
			_changeUrl = _changeUrl + "&to=" + to;
		}
		
		if (data != 'nourlchange') {
			//window.history.pushState("object or string", "Filter", _changeUrl);
		}
		$.ajax({
			type: "get",
			url: _changeUrl,
			data: {},
			datatype: "html",
			beforeSend: function() {
				if (is_loader_stop != 'YES') {
					$('.ajaxloader').show();
				}
			}
		}).done(function(data) {
			$('.ajaxloader').hide();
			if (data.length == 0) {
				$('.ajaxloader').hide();
				return false;
			}
			if (data['show_msg']) {
				Lobibox.notify(data.type, {
					rounded: false,
					delay: 5000,
					delayIndicator: true,
					position: "top right",
					msg: data.message
				});
			}

			if (data['reloadPage']) {
				location.reload();
			}
			if (data['refresh']) {
				var url = "{{Request::getRequestUri()}}";
				var _changeUrl = url;
				paginate(_changeUrl, 'nourlchange');
			} else {
				if (data['apppendid']) {
					$("#" + data['apppendid']).empty().append(JSON.parse(data['body']));
				} else {
					window.history.pushState("object or string", "Filter", _changeUrl);
					if (data['body']) {
						$("#result").empty().append(JSON.parse(data['body']));
					}
				}
			}

			// 2. Update the Tab Counts (NEW CODE)
	        if (data['tab_counts']) {
	            $('#count_all').text(data['tab_counts']['all']);
	            $('#count_draft').text(data['tab_counts']['draft']);
	            $('#count_for_review').text(data['tab_counts']['for_review']);
	            $('#count_approved').text(data['tab_counts']['approved']);
	            $('#count_new').text(data['tab_counts']['new']);
	            $('#count_archived').text(data['tab_counts']['archived']);
	        }
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			$('.ajaxloader').hide();
		});
	}

	function serach(is_loader_stop = '') {
		
		var order_by = '';
		var from = '';
		var to = '';
		var name = '';
		var email = '';
		var sort_by = '';
		var type = '';
		if ($("select[name='order_by'] option:selected").val()) {
			order_by = $("select[name='order_by'] option:selected").val();
		}
		if ($("select[name='sort_by'] option:selected").val()) {
			sort_by = $("select[name='sort_by'] option:selected").val();
		}
		if ($("input[name='from']").val()) {
			from = $("input[name='from']").val();
		}
		if ($("input[name='to']").val()) {
			to = $("input[name='to']").val();
		}
		if ($("input[name='name']").val()) {
			name = $("input[name='name']").val();
		}
		if ($("input[name='email']").val()) {
			email = $("input[name='email']").val();
		}
		if ($("input[name='search_type']").val()) {
			type = $("input[name='search_type']").val();
		}
		var url = REQUEST_URL;
		var _URL_ = url;
		var customURL = "?search=" + name;
		if (order_by != '') {
			customURL = customURL + "&status=" + order_by;
		}
		if (from != '') {
			customURL = customURL + "&from=" + from;
		}
		if (to != '') {
			customURL = customURL + "&to=" + to;
		}
		if (email != '') {
			customURL = customURL + "&email=" + email;
		}
		if (sort_by != '') {
			customURL = customURL + "&sortby=" + sort_by;
		}
		if (type != '') {
			customURL = customURL + "&type=" + type;
		}
		var _changeUrl = url + customURL;
		//window.history.pushState("object or string", "Filter", _changeUrl);
		$.ajax({
			type: "get",
			url: _changeUrl,
			data: {},
			datatype: "html",
			beforeSend: function() {
				if (is_loader_stop != 'YES') {
					$('.ajaxloader').show();
				}
			}
		}).done(function(data) {
			$('.ajaxloader').hide();
			if (data.length == 0) {
				$('.ajaxloader').hide();
				return false;
			}
			if (data['body']) {
				$("#result").empty().append(JSON.parse(data['body']));
			}

			// 2. Update the Tab Counts (NEW CODE)
	        if (data['tab_counts']) {
	            $('#count_all').text(data['tab_counts']['all']);
	            $('#count_draft').text(data['tab_counts']['draft']);
	            $('#count_for_review').text(data['tab_counts']['for_review']);
	            $('#count_approved').text(data['tab_counts']['approved']);
	            $('#count_published').text(data['tab_counts']['published']);
	            $('#count_rejected').text(data['tab_counts']['rejected']);
	            $('#count_new').text(data['tab_counts']['new']);
	            $('#count_archived').text(data['tab_counts']['archived']);
	        }
		}).fail(function(jqXHR, ajaxOptions, thrownError) {
			$('.ajaxloader').hide();
		});
	}

	function AjaxActionTableDrow(obj) {
		var _action = obj.getAttribute('data-action'),
			title = obj.getAttribute('data-title'),
			refresh = obj.getAttribute('data-refresh'),
			reload = obj.getAttribute('data-reload');

		var _type = 'GET';
		if (title == 'Delete') {
			_type = 'DELETE';
		}
		Lobibox.confirm({
			title: title + ' Confirmation',
			msg: 'Are you sure you, want to ' + title + '?',
			callback: function($this, type, ev) {
				if (type === 'yes') {
					$.ajax({
						type: _type,
						url: _action,
						beforeSend: function() {
							$('.ajaxloader').show();
						},
						success: function(data) {
							$('.ajaxloader').hide();
							serach();
							Lobibox.notify(data.type, {
								rounded: false,
								delay: 4000,
								delayIndicator: true,
								position: "top right",
								msg: data.message
							});

							if (reload == 'yes') {
								location.reload();
							}
						},
						error: function(data) {
							console.log('Error:', data);
						}
					});
				} else {
					return false;
				}
			}
		});
	}

	function confirmationBoxAjax(id, title, href) {
		Lobibox.confirm({
			title: title + ' Confirmation',
			msg: 'Are you sure you, want to ' + title + '?',
			callback: function($this, type, ev) {
				if (type === 'yes') {
					paginate(href, 'nourlchange');
					window.location.href = href;
				} else {
					return false;
				}
			}
		});
	}
</script>
@endsection