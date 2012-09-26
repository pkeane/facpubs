var Dase = {};

$(document).ready(function() {
	Dase.initDelete('login');
	Dase.initDelete('set');
	Dase.initDelete('item_metadata');
//	Dase.initToggle('target');
//	Dase.initToggle('email');
	//Dase.initSortable('set');
	Dase.initSortableTable('set');
	Dase.initUserPrivs();
	//Dase.initFormDeleteOrig();
	Dase.initLabSelector();
	Dase.initToggleCheck();
    Dase.initColorbox();
    Dase.initBulkAdd();
    Dase.initCsvUpload();
    Dase.initEditMetadataValue();
    Dase.initEditItem();
    //Dase.initEditItemForm();
    Dase.initInactiveLinks();

		//from facpubs/test
	Dase.initDelete('topMenu');
	Dase.initDelete('proxies');
	Dase.initDelete('uploaded_files');
	Dase.initToggle('target');
	Dase.initToggle('email');
	Dase.initToggle('lines');

	Dase.initFormToggle();
	Dase.initToggle('manage_sections');
	Dase.initUserPrivs();
	//Dase.initFormDelete();
	Dase.initFoldSectionLines();
	Dase.initCheckAll();
	Dase.initCancelForm();
	Dase.initSectionHead();
	Dase.initReview();
	Dase.initSectionSortable('sections');
	Dase.initDeleteSectionHeader();
	Dase.initEnableLinesSort();
	Dase.initMinimizeProgress();
	Dase.initQuickCheck();
	Dase.initDir();

	Dase.getURLFragment();
	Dase.initHelpLinks();

	//cvproc
	Dase.resizeIt();
	Dase.initCv();
	Dase.initHelp();
	
	//citations tab menu
	// Dase.citationmenu(); 

	// year form editing - temporary see bottom
	Dase.initYearToggle();
	// testing button toggle for peer/creative
	Dase.initPeer();
	Dase.initCertify();
	
});


Dase.initInactiveLinks = function() {
    $("li.disabled a").click(function() { return false; });
};

/*
Dase.initEditItemForm = function() {
    $('#edit_item_form').submit(function() {
        $.ajax({  
            type: "POST",  
            url: $(this).attr('action'),  
            data: $(this).serialize(),  
            success: function(data) {  
                location.reload();
            }  
        });
        return false;
    });
};
*/

Dase.initEditItem = function() {
    $('a#edit-item').click(function() {
        var href = $(this).attr('href');
        $.colorbox({
            href:href,
            opacity: 0.5,
            width: 900,
            onComplete: function() {
                //Dase.initEditItemForm();
                Dase.initFormDelete();
                $('#closeColorbox').click(function() {$.colorbox.close();});
            },
        });
        return false;
    });
    $('a#edit-item-swap').click(function() {
        var href = $(this).attr('href');
        $.colorbox({
            href:href,
            opacity: 0.5,
            onComplete: function() {
                Dase.initFormDelete();
                $('#closeColorbox').click(function() {$.colorbox.close();});
            },
        });
        return false;
    });
    $('a#edit-item-metadata').click(function() {
        var href = $(this).attr('href');
        $.colorbox({
            href:href,
            width: '700',
            opacity: 0.5,
            onComplete: function() {
                Dase.initEditMetadataValue();
                Dase.initBulkAdd();
                Dase.initDelete('item_metadata');
                $('#closeColorbox').click(function() {$.colorbox.close();});
            },
        });
        return false;
    });
};

Dase.initCsvUpload = function() {
    $('a#csv').click(function() {
        var href = $(this).attr('href');
        $.colorbox({
            href:href,
            width: '480',
            opacity: 0.5,
            onComplete: function() {
                $('#closeColorbox').click(function() {$.colorbox.close();});
            }
        });
        return false;
    });
};

Dase.initBulkAdd = function() {
    $('#bulk_add').find('select[name="attribute_id"]').change(function() {
        var att_id = $('select[name="attribute_id"] option:selected').val();
        var url = 'content/attribute/'+att_id+'/input_form';
        $.get(url,function(data) { $('#att_input_form').html(data); });
    });
    $('#bulk_add').submit(function() {
        var items_input = $(this).find('input[name="items"]');
        var matches = [];
        $("#items input:checked").each(function() {
            matches.push(this.value);
        });
        items_input.attr('value',matches.join('|'));
    });
};

Dase.initEditMetadataValue = function() {
    $('#item_metadata').find('a.edit').click(function() {
        $(this).parents('tr').find('span.current_value').hide();
        var target = $(this).parents('tr').find('span.value_input_form');
        var url = $(this).attr('href');
        $.get(url,function(data) { target.html(data); });
        return false;
    });
};

Dase.initColorbox = function() {
    $("#loclink").colorbox(
            {iframe:true, innerWidth:800, innerHeight:500,onClosed: function() {location.reload()}}
            );
};

Dase.initToggleCheck = function() {
	var checked = false;
	$('#toggle_check').click(function() {
		if (checked) {
			$('table#items').find('input[type="checkbox"]').attr('checked',false);
			checked = false;
		} else {
			$('table#items').find('input[type="checkbox"]').attr('checked',true);
			checked = true;
		}
		return false;
	});
};

Dase.initLabSelector = function() {
	$('ul#lab_selector li').hover(function() {
		$('#banners img').hide();
		var id = $(this).attr('class');
		$('#'+id).show();
	});
	$('#banners').hover(function() {
		$('#banners img').hide();
		$('#front').show();
	});
};



/*
Dase.initToggle = function(id) {
	$('#'+id).find('a[class="toggle"]').click(function() {
		var id = $(this).attr('id');
		var tar = id.replace('toggle','target');
		$('#'+tar).toggle();
		return false;
	});	
};
*/

Dase.initFormDeleteOrig = function() {
	$("form[method='delete']").submit(function() {
		if (confirm('are you sure?')) {
			var del_o = {
				'url': $(this).attr('action'),
				'type':'DELETE',
				'success': function() {
					location.reload();
				},
				'error': function() {
					alert('sorry, cannot delete');
				}
			};
			$.ajax(del_o);
		}
		return false;
	});
};

Dase.initDelete = function(id) {
	$('#'+id).find("a.delete").click(function() {
		if (confirm('are you sure?')) {
			var del_o = {
				'url': $(this).attr('href'),
				'type':'DELETE',
				'success': function(resp) {
					if (resp.location) {
						location.href = resp.location;
					} else {
						location.reload();
					}
				},
				'error': function() {
					alert('sorry, cannot delete');
				}
			};
			$.ajax(del_o);
		}
		return false;
	});
};

Dase.initSortable = function(id) {
	$('#'+id).sortable({ 
		cursor: 'crosshair',
		opacity: 0.6,
		revert: true, 
		start: function(event,ui) {
			ui.item.addClass('highlight');
		},	
		stop: function(event,ui) {
			$('#proceed-button').addClass('hide');
			$('#unsaved-changes').removeClass('hide');
			$('#'+id).find("li").each(function(index){
				$(this).find('span.key').text(index+1);
			});	
			ui.item.removeClass('highlight');
		}	
	});
};

Dase.initSortableTable = function(id) {
	$('#'+id).sortable({ 
		cursor: 'crosshair',
		opacity: 0.6,
		revert: true, 
		start: function(event,ui) {
			ui.item.addClass('highlight');
		},	
		stop: function(event,ui) {
			var order_data = [];
			$('#'+id).find("tr").each(function(index){
				$(this).find('span.key').text(index);
				order_data[order_data.length] = $(this).attr('id');
			});	
			var url = $('link[rel="items_order"]').attr('href');
			var _o = {
				'url': url,
				'type':'POST',
				processData: false,
				data: order_data.join('|'),
				'success': function(resp) {
					//alert(resp);
					//location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o);
			ui.item.removeClass('highlight');
		}	
	});
};

Dase.initUserPrivs = function() {
	$('#user_privs').find('a').click( function() {
		var method = $(this).attr('data-method');
		var url = $(this).attr('href');
		var _o = {
			'url': url,
			'type':method,
			'success': function(resp) {
				alert(resp);
				location.reload();
			},
			'error': function() {
				alert('sorry, there was a problem');
			}
		};
		$.ajax(_o);
		return false;
	});


Dase.getURLFragment = function() {
	var parts = location.href.split('#');
	if (parts[1]) {
	var frag = parts[1];
	var line_id = frag.replace('line','');
	$('#'+line_id).effect("highlight", {color: '#ff6'}, 5000);
}
};

Dase.initHelpLinks = function() {
	$('.inner-cont').find('a.help_link').click(function() {
		var href = $(this).attr('href');
		$.colorbox({
			href:href,
			width: '500px',
			opacity: 0.5,
			onComplete: function() {
				$('#closeColorbox').click(function() {$.colorbox.close();});
			}
		}); 
		return false;
	});
}

Dase.initDir = function() {
	$('a#dir').click(function() {
		var href = $(this).attr('href');
		$.colorbox({ 
			href:href,
			opacity:0.4,
			onComplete: function() {
				$('#directory_form').submit(function() {
					var url = $(this).attr('action');
					var params = 'lastname='+$(this).find('input[name="lastname"]').attr('value');
					$.colorbox({ 
						href: url+'?'+params,
						opacity:0.4,
						onComplete: function() {
							$('ul.results li a').click(function() {
								$('#proxy_eid').attr('value',$(this).attr('href'));
								$.colorbox.close();
								return false;
							});
							$('#closeColorbox').click(function() {$.colorbox.close();});
						},
				});
				return false;
			});
			$('#closeColorbox').click(function() {$.colorbox.close();});
		},
});
return false;
});
};

Dase.initCheckLines = function() {
	$('#check_uncheck_lines').click(function() {
		if ($(this).attr('checked')) {
			$(this).parents('form').find('input[type="checkbox"]').attr('checked','checked');
		} else {
			$(this).parents('form').find('input[type="checkbox"]').removeAttr('checked');
		}
	});
};

Dase.initHelp = function() {
	$('ul#help').find('li a').click(function() {
		$(this).siblings('ul').toggle();
		$(this).toggleClass('open');
		return false;
	});
};

Dase.initMinimizeProgress = function() {
	$('a#minimize_progress').click(function() {
		$(this).parents('div').find('div.step').toggle();
		$(this).parents('div').find('div.arrow').toggle();
		return false;
	});

};

Dase.initQuickCheck = function() {
	$('#lines').find('form.quick_check input[type="checkbox"]').change(function() {
		var url = $(this).parents('form').attr('action');
		var value = $(this).attr('checked');
		var _o = {
			'url': url,
			'type':'PUT',
			processData: false,
			data: value,
			'success': function(resp) {
				//alert(resp);
				//location.reload();
			},
			'error': function() {
				alert('sorry, there was a problem');
			}
		};
		$.ajax(_o);
	});
};

Dase.initEnableLinesSort = function() {
	$('#lines').find('a.enable_sort').click(function() {
		var id = $(this).attr('href');
		$(this).text('drag & drop to re-order | click to disable');
		$(this).addClass('sorton'); // added for link styling
		var thelink = $(this);
		$(this).click(function() {
			$(this).removeClass('sorton');  // added for link styling
			$('#'+id).sortable("destroy");
			thelink.text('re-order citations');
			Dase.initEnableLinesSort();
		});
		Dase.initLinesSortable(id);
		return false;
	});
};

Dase.initDeleteSectionHeader = function() {
	$('#sections').find('input[type="button"][value="delete empty section"]').click(function() { // I changed [class="delete"] to [value="delete empty section"] becuase there were class-name conflicts with button styling
		if (confirm('are you sure?')) {
			var url = $(this).parents('form').attr('action');
			var del_o = {
				'url': url,
				'type':'DELETE',
				'success': function(resp) {
					if (resp.location) {
						location.href = resp.location;
					} else {
						location.reload();
					}
				},
				'error': function() {
					alert('sorry, cannot delete');
				}
			};
			$.ajax(del_o);
		}
		return false;
	});
};

Dase.initLinesSortable = function(id) {
	$('#'+id+" tbody").sortable({
		cursor: 'crosshair',
		opacity: 0.6,
		revert: false,
		start: function(event,ui) {
			ui.item.addClass('highlight');
		},
		stop: function(event,ui) {
			var order_data = [];
			$('#'+id).find("tr").each(function(index){
				order_data[order_data.length] = $(this).attr('id');
			});	
			var url = $('link[rel="lines_order"]').attr('href');
			var _o = {
				'url': url,
				'type':'POST',
				processData: false,
				data: order_data.join('|'),
				'success': function(resp) {
					//alert(resp);
					//location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o);
			ui.item.removeClass('highlight');
			ui.item.addClass('sub-highlight');
		}
		}).disableSelection();
	};

	Dase.initReviewx = function() {
		$('#lines a.review').click(function() {
			var href = $(this).attr('href');
			$.colorbox({
				href:href,
				width: '600px',
				speed: 200,
				opacity: 0.4,
				onComplete: function() {
					$('#closeColorbox').click(function() {$.colorbox.close();});
				}
			}); 
			return false;
		});

	};

	Dase.initReview = function() {
		$('#lines a.review').click(function() {
			$(this).parents('p').find('a').toggle();
			/*
			$(this).parents('tr').find('td.citation').addClass('reviewed');
			$(this).parents('tr').find('td.year').addClass('reviewed');
			$(this).parents('tr').find('td.is_peer').addClass('reviewed');
			*/
			var url = $(this).attr('href');
			var _o = {
				'url': url,
				'type':'POST',
				processData: false,
				'success': function(resp) {
					//alert(resp);
					//location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o);
			return false;
		});
	};
      
	Dase.initSectionHead = function() {
		$('#sections a').click(function() {
			$(this).parents('li').find('form').toggle();
			$(this).parents('li').find('span.sec-title').toggle();
			return false;
		});
	};
        
	Dase.initCancelForm = function() {
		$('div.revision_form').find('input[value="cancel"]').click(function() {
			$(this).parents('div.revision_form').hide();
			return false;
		});
	};

	/*
	 Dase.initRadios = function() {
		 $('input[type="radio"]').parents('td.y').css('background-color','#eeffeb');
		 $('input[type="radio"]').parents('td.n').css('background-color','#ffedeb');
		 $('input:checked').parents('td.y').css('background-color','#6c3');
		 $('input:checked').parents('td.n').css('background-color','#c66');
		 $('input[type="radio"]').click(function() {
			 Dase.initRadios();
		 });
	 };
	 */

	Dase.initCheckAll = function() {
		$('input.control_check').click(function() {
			var dd = $(this).attr('id');
			if ($(this).attr('checked')) {
				$('input.'+dd+'_checkbox').attr('checked','checked');
			} else {
				$('input.'+dd+'_checkbox').removeAttr('checked');
			}
		});

	};

	Dase.initFoldSectionLines = function() {
		$('#lines div.sec_head a').click(function() {
			var target_id = 'sec_body'+$(this).attr('href');
			$(this).parents('div.sec_head').find('img').toggle();
			$('#'+target_id).toggle();
			return false;
		});
	};

        // edit form toggle
	Dase.initFormToggle = function() {
		$('#lines').find('a[class="edit_form_toggle"]').click(function() {	
			var parent_li = $(this).parents('li');
			parent_li.find('div.check input').toggle(); 
			parent_li.find('div.year').toggle();
			var id = $(this).attr('id');
			var url = $(this).attr('href');
			var tar = id.replace('toggle','target');
			$('#'+tar).toggle();
			var _o = {
				'url': url,
				'type': 'GET',
				'success': function(resp) {
					$('#'+tar).html(resp).find('input[type="button"][value="cancel"]').click(function() {
						/*  old checkboxes
						parent_li.find('div.check input').toggle();
						parent_li.find('div.year').toggle();
						*/
						var target_id = $(this).attr('class').split(' ')[0]; // added to accommodate button styling
						$('#'+target_id).hide();
						return false;
					});
					Dase.initHelpLinks();
					Dase.initFormDelete(tar);
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o);
			return false;
		});	
	};



	Dase.initToggle = function(id) {
		$('#'+id).find('a[class="toggle"]').click(function() {
			var id = $(this).attr('id');
			var tar = id.replace('toggle','target');
			$('#'+tar).toggle();
			$('#'+tar).find('input[type="button"][value="cancel"]').click(function() {	
				var target_id = $(this).attr('class').split(' ')[0]; // added to accommodate button styling
				$('#'+target_id).hide();
				return false;
			});
			return false;
		});	
	};

	Dase.initFormDelete = function(id) {
		$("#"+id+" form[method='delete']").submit(function() {
			if (confirm('are you sure ?')) { // citation delete via edit form
				var del_o = {
					'url': $(this).attr('action'),
					'type':'DELETE',
					'success': function() {
						location.reload();
						//location.href = location.href.replace(/\?section=.*$/,'');
					},
					'error': function() {
						alert('sorry, cannot delete');
					}
				};
				$.ajax(del_o);
			}
			console.log();
			return false;
		});
	};


	Dase.initSectionSortable = function(id) {
		$('#'+id).sortable({ 
			cursor: 'crosshair',
			opacity: 0.6,
			revert: true, 
			start: function(event,ui) {
				ui.item.addClass('highlight');
			},	
			stop: function(event,ui) {
				var order_data = [];
				$('#proceed-button').addClass('hide');
				$('#unsaved-changes').removeClass('hide');
				$('#'+id).find("li").each(function(index){
					order_data[order_data.length] = $(this).attr('id');
					//$(this).find('span.key').text(index+1);
				});	
				var url = $('link[rel="section_order"]').attr('href');
				//alert(order_data);
				var _o = {
					'url': url,
					'type':'POST',
					processData: false,
					data: order_data.join('|'),
					'success': function(resp) {
						//alert(resp);
						//location.reload();
					},
					'error': function() {
						alert('sorry, there was a problem');
					}
				};
				$.ajax(_o);
				ui.item.removeClass('highlight');
			}	
		});
	};

	Dase.initUserPrivs = function() {
		$('#user_privs').find('a').click( function() {
			var method = $(this).attr('class');
			var url = $(this).attr('href');
			var _o = {
				'url': url,
				'type':method,
				'success': function(resp) {
					alert(resp);
					location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o);
			return false;
		});
	};

	//from cvproc
	//


	Dase.ajaxMessage = function(text) {
		$('#ajaxMessage').html(text).fadeIn('fast').delay(500).fadeOut('fast');
	};


	Dase.submitCvText = function() {
		var ta = $('form#text_form textarea.cv');
		ta.effect("highlight", {}, 1500);
		var str = ta.val();
		var url = $('form#text_form').attr('action');
		var _o = {
			'url': url,
			'type':'PUT',
			'data':str,
			'success': function(resp) {
				Dase.ajaxMessage(resp);
			},
			'error': function() {
				alert('sorry, there was a problem');
			}
		};
		$.ajax(_o);
		Dase.resizeIt();
	};

	Dase.initRevertCVText = function() {
		$('#revert').click(function() {
			if (confirm('are you sure?')) {
				var get_o = {
					'url': $(this).attr('href'),
					'type':'GET',
					'success': function(resp) {
						$('form#text_form textarea.cv').val(resp);
					},
					'error': function() {
						alert('sorry, there was an error');
					}
				};
				$.ajax(get_o);
			}
			return false;
		});
	};

	Dase.initShowLines = function() {
		$('a#lines').click(function() {
			var lines_url = $(this).attr('href');
			var str = $('form#text_form textarea.cv').val();
			var url = $('form#text_form').attr('action');
			//first update
			var _o = {
				'url': url,
				'type':'PUT',
				'data':str,
				'success': function() {
					var get_o = {
						'url': lines_url,
						'type':'GET',
						'success': function(subresp) {
							$.colorbox({
								html:subresp,
								onComplete: function() {
									$('#closeColorboxButton').click(function() {
										$.colorbox.close();
									});
									Dase.initCheckLines();
								},
						}); 
					},
					'error': function() {
						alert('sorry, there was an error');
					}
				};
				$.ajax(get_o);
			},
			'error': function() {
				alert('sorry, there was a problem');
			}
		};
		$.ajax(_o);
		return false;
	});
};

Dase.initCv = function() {
	var form = $('form#text_form');
	Dase.initRevertCVText();
	Dase.initShowLines();
	$('form#text_form textarea.cv').data('timeout',null).keyup(function(){
		clearTimeout($(this).data('timeout'));
		$(this).data('timeout', setTimeout(Dase.submitCvText, 3000));
	});
	$('#inline').colorbox({ 
		open:true,
		inline:true,
		opacity:0.4,
	onLoad: function() { $('#instructions').toggle(); },	
onCleanup: function() { $('#instructions').toggle(); },	
	});
};


Dase.resizeIt = function() {
	var txtarea = $('form#text_form textarea.cv');
	var str = txtarea.val();
	if (str) {
		var cols = txtarea.attr('cols');
		var rows = str.split("\n").length
		txtarea.attr('rows',rows);
	}
};

};

// -----------   year form - temporary

Dase.initYearToggle = function() {
	$('#lines').find('a[class="year_form_toggle"]').click(function() {
		var id = $(this).attr('id');
		var url = $(this).attr('href');
		var tar = id.replace('toggle','target');
		$('#'+tar).toggle();
		//var _o = {
		//	'url': url,
		//	'type': 'GET',
		//	'success': function(resp) {
		//		$('#'+tar).html(resp).find('input[type="button"][value="cancel"]').click(function() {
		//			/*  old checkboxes
		//			parent_li.find('div.check input').toggle();
		//			parent_li.find('div.year').toggle();
		//			*/
		//			var target_id = $(this).attr('class').split(' ')[0]; // added to accommodate button styling
		//			$('#'+target_id).hide();
		//			return false;
		//		});
		//		Dase.initHelpLinks();
		//		Dase.initFormDelete(tar);
		//	},
		//	'error': function() {
		//		alert('sorry, there was a problem');
		//	}
		//}
		//$.ajax(_o);
		return false;
	});	
};
	Dase.initPeer = function() {
		$('#lines a.peer').click(function() {
			$(this).parents('p').find('a').toggle();
			var url = $(this).attr('href');
			var form_dat = "checked="+$(this).attr('id');
			var _o = {
				'url': url,
				'type':'POST',
				data: form_dat,
				'success': function(resp) {
					//alert(resp);
					//location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o); 
			return false;
		});
	};

	Dase.initCertify = function() {
		$('form#certify_record_form').submit(function() {
			var url = $(this).attr('action');
			var _o = {
				'url': url,
				'type':'POST',
				'success': function(resp) {
					$('#confirm').fadeIn().delay(2000).fadeOut();
					location.reload();
				},
				'error': function() {
					alert('sorry, there was a problem');
				}
			};
			$.ajax(_o); 
			return false;
		});
	};

