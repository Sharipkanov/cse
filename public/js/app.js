$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $('[data-disabled-select]').prop('disabled', true);

    $('[data-switcher-select]').change(function () {
        var $selectChanger = $(this),
            $$selectChangerOptionSelected = $selectChanger.find('option:selected'),
            $selectTarget = $('#'.concat($selectChanger.data('switcher-select')));

        $selectTarget.val($$selectChangerOptionSelected.val()).change();
    });

    var $specialitiesDropDown = $('#specialities-drop-down'),
        $specialitiesList = $('#specialities-list'),
        $specialityInput = $('#speciality-input'),
        $specialitySearchInput = $('#speciality-search-input'),
        specialitiesData = {};

    var setSpecialitiesData = function () {
        var $specialityLinks = $specialitiesList.find('a');

        if($specialityLinks.length) {
            $specialityLinks.each(function(key, link) {
                var $link = $(link);

                specialitiesData[$link.data('code')] = $link.data('id');
            });
        }
    };

    setSpecialitiesData();

    var setSpeciality = function() {
        var ids = [];
        $.each(specialitiesData, function(key, id){
            ids.push(id);
        });

        $specialityInput.val(ids.join());
    };

    $specialitySearchInput.keyup(function() {
        var $input = $(this),
            inputValue = $input.val();

        $.ajax({
            type: "POST",
            url: '/expertise/specialities.html',
            data: {query: inputValue},
            success: function(response) {
                $specialitiesDropDown.removeClass('danger');
                $specialitiesDropDown.empty();

                for(var i=0; i<response.length; i++) {
                    var speciality = response[i],
                        specialityCode = speciality['code'];

                    if(specialitiesData[specialityCode] === undefined) {
                        $('<a href="#" class="speciality-choose" data-code="'+ specialityCode +'" data-id="'+ speciality['id'] +'">'+ specialityCode + ' - ' + speciality['name'] +'</a>').appendTo($specialitiesDropDown);
                    }
                }

                if(!$specialitiesDropDown.hasClass('active')) $specialitiesDropDown.addClass('active');
            },
            error: function(response) {
                if(response.status === 422) {
                    $specialitiesDropDown.addClass('danger');
                    $specialitiesDropDown.empty();
                    $('<p>'+ response['responseJSON']['message'] +'</p>').appendTo($specialitiesDropDown);

                    if(!$specialitiesDropDown.hasClass('active')) $specialitiesDropDown.addClass('active');
                }
            },
            dataType: 'json'
        });
    });

    $(document).on('click', '.speciality-choose', function(e) {
        e.preventDefault();

        var $speciality = $(this),
            specialityId = $speciality.data('id'),
            specialityCode = $speciality.data('code');

        $specialitiesList.append($('<li><span>'+ $speciality.html() +'</span><a href="#" class="speciality-remove" data-id="'+ specialityId  +'" data-code="'+ specialityCode +'"><i class="uk-icon-remove"></i></a></li>'));
        specialitiesData[specialityCode] = specialityId;
        setSpeciality();
        $specialitySearchInput.val('');

        if($specialitiesDropDown.hasClass('active')) $specialitiesDropDown.removeClass('active');
    });

    $(document).on('click', '.speciality-remove', function(e) {
        e.preventDefault();

        var $speciality = $(this),
            specialityId = $speciality.data('speciality-id'),
            specialityCode = $speciality.data('code');

        if(specialitiesData[specialityCode]) {
            specialitiesData[specialityCode] = null;
            delete specialitiesData[specialityCode];
            setSpeciality();
            $speciality.closest('li').remove();
        }
    });


    $(document).on('change', '#expertise-region-select', function() {
        var $select = $(this),
            $agencySelect = $('#expertise-agency-select'),
            data = {
                get_agency: 1,
                region_id: parseInt($select.val())
            };

        $agencySelect.prop('disabled', true);

        $.post('/expertise/agencies.html', data, function(response) {
            $agencySelect.find('option').remove();
            $agencySelect.prop('disabled', false);

            $.each(response, function(key, agency) {
                $('<option value="'+ agency['id'] +'">'+ agency['name'] +'</option>').appendTo($agencySelect);
            });
        }, 'json');
    });

    $(document).on('change', '#expertise-organ-select', function() {
        var $select = $(this),
            organId = parseInt($select.val()),
            $expertiseOrganName = $('#expertise-organ-name');

        $('.expertise-extra-fields').removeClass('uk-hidden');
         (organId === 1) ? $expertiseOrganName.removeClass('uk-hidden') : $expertiseOrganName.addClass('uk-hidden');

        if($.inArray(organId, [1, 8, 9]) > -1) {
            $('#expertise-user-rank').addClass('uk-hidden');

            if(organId === 8) $('#expertise-user-position').addClass('uk-hidden');
        }

    });

    var $correspondenceSearchInput = $('#correspondence-search-input'),
        $correspondenceInput = $('#correspondence-input'),
        $correspondenceDropDown = $('#correspondence-drop-down');

    $correspondenceSearchInput.keyup(function() {
        var $input = $(this),
            inputValue = $input.val();

        $.ajax({
            type: "POST",
            url: '/correspondence/correspondence.html',
            data: {correspondence: inputValue},
            success: function(response) {
                $correspondenceDropDown.removeClass('danger');
                $correspondenceDropDown.empty();

                for(var i=0; i<response.length; i++) {
                    var correspondence = response[i],
                        correspondenceCode = correspondence['code'];

                    $('<a href="#" class="correspondence-choose" data-id="'+ correspondence['id'] +'">'+ correspondence['name'] +'</a>').appendTo($correspondenceDropDown);
                }

                if(!$correspondenceDropDown.hasClass('active')) $correspondenceDropDown.addClass('active');
            },
            error: function(response) {
                if(response.status === 422) {
                    $correspondenceDropDown.addClass('danger');
                    $correspondenceDropDown.empty();
                    $('<p>'+ response['responseJSON']['message'] +'</p>').appendTo($correspondenceDropDown);

                    if(!$correspondenceDropDown.hasClass('active')) $correspondenceDropDown.addClass('active');
                }
            },
            dataType: 'json'
        });
    });

    $(document).on('click', '.correspondence-choose', function(e) {
        e.preventDefault();

        var $link = $(this);

        $correspondenceInput.val($link.data('id'));
        $correspondenceSearchInput.val($link.html());

        $correspondenceDropDown.removeClass('active');
    });

    $(document).on('click', '.approve-type', function(e) {
        e.preventDefault();

        var $label = $(this),
            $input = $label.find('input'),
            $approveInfo = $('#approve-info');

        $input.prop('checked', true);

        (parseInt($input.val()) === 2) ? $approveInfo.removeClass('uk-hidden') : $approveInfo.addClass('uk-hidden');
    });

    var $correspondentSearchInput = $('#correspondent-search-input'),
        $correspondentInput = $('#correspondent-input'),
        $correspondentDropDown = $('#correspondent-drop-down');

    $correspondentSearchInput.keyup(function() {
        var $input = $(this),
            inputValue = $input.val();

        $.ajax({
            type: "POST",
            url: '/correspondence/correspondent.html',
            data: {correspondent: inputValue, get_correspondent: 1},
            success: function(response) {
                $correspondentDropDown.removeClass('danger');
                $correspondentDropDown.empty();

                for(var i=0; i<response.length; i++) {
                    var correspondent = response[i],
                        correspondentCode = correspondent['code'];

                    $('<a href="#" class="correspondent-choose" data-id="'+ correspondent['id'] +'">'+ correspondent['name'] +'</a>').appendTo($correspondentDropDown);
                }

                if(!$correspondentDropDown.hasClass('active')) $correspondentDropDown.addClass('active');
            },
            error: function(response) {
                if(response.status === 422) {
                    $correspondentDropDown.addClass('danger');
                    $correspondentDropDown.empty();
                    $('<p>'+ response['responseJSON']['message'] +'</p>').appendTo($correspondentDropDown);

                    if(!$correspondentDropDown.hasClass('active')) $correspondentDropDown.addClass('active');
                }
            },
            dataType: 'json'
        });
    });

    var $taskSearchInput = $('#task-search-input'),
        $taskInput = $('#task-input'),
        $taskDropDown = $('#task-drop-down');

    $taskSearchInput.keyup(function() {
        var $input = $(this),
            inputValue = $input.val();

        $.ajax({
            type: "POST",
            url: '/task/task.html',
            data: {task: inputValue, get_task: 1},
            success: function(response) {
                $taskDropDown.removeClass('danger');
                $taskDropDown.empty();

                for(var i=0; i<response.length; i++) {
                    var task = response[i];

                    $('<a href="#" class="task-choose" data-id="'+ task['id'] +'">'+ task['name'] +'</a>').appendTo($taskDropDown);
                }

                if(!$taskDropDown.hasClass('active')) $taskDropDown.addClass('active');
            },
            error: function(response) {
                if(response.status === 422) {
                    $taskDropDown.addClass('danger');
                    $taskDropDown.empty();
                    $('<p>'+ response['responseJSON']['message'] +'</p>').appendTo($taskDropDown);

                    if(!$taskDropDown.hasClass('active')) $taskDropDown.addClass('active');
                }
            },
            dataType: 'json'
        });
    });

    $(document).on('click', '.task-choose', function(e) {
        e.preventDefault();

        var $link = $(this);

        $taskInput.val($link.data('id'));
        $taskSearchInput.val($link.html());

        $taskDropDown.removeClass('active');
    });

    $(document).on('click', '.correspondent-choose', function(e) {
        e.preventDefault();

        var $link = $(this);

        $correspondentInput.val($link.data('id'));
        $correspondentSearchInput.val($link.html());

        $correspondentDropDown.removeClass('active');
    });

    $(document).on('click', function(e) {
        var $target = $(e.target);

        if($target.closest('.drop-down').length < 1) $('.drop-down').removeClass('active');
    });

    $('[data-uk-timepicker]').change(function (e) {
        var $input = $(this),
            time = $input.val();
        if($.inArray(time, ["09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30"]) < 0) {
            $input.val("09:00");
        }
    });

    $(document).on('change', '.speciality-checkbox', function() {
        var $currentCheckbox = $(this),
            $currentCheckboxState = $(this).prop('checked'),
            $currentCheckboxConnected = $('[data-speciality="'+ $currentCheckbox.data('speciality') +'"]');

        if($currentCheckboxState) {
            $currentCheckboxConnected.not($currentCheckbox).prop('checked', false);
            $currentCheckboxConnected.not($currentCheckbox).closest('label').hide();
        } else {
            $currentCheckboxConnected.prop('checked', false);
            $currentCheckboxConnected.not($currentCheckbox).closest('label').show();
        }
    });

    $(document).on('keypress', '[data-number]', function (e) {

        return e.charCode >= 48 && e.charCode <= 57
    });

    $(document).on('change', '#reason_for_suspension', function() {
        var $select = $(this),
            suspensionReason = $select.val();

        if(['Ходатайство', 'Командировка'].indexOf(suspensionReason) > -1) {
            console.log(1);
        } else if(['Больничный лист'].indexOf(suspensionReason) > -1) {
            console.log(2);
        } else if(['Вызов в суд', 'Участие в комплексной экспертизе'].indexOf(suspensionReason) > -1) {
            console.log(3);
        }
    });
});