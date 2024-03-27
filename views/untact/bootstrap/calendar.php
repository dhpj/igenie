<div id="mArticle">
  <div class="form_section">
    <div class="inner_tit">
			<h3>일정관리</h3>
    </div>
  <div class="white_box">
    <div id="menu">
        <span id="menu-navi">
        <button type="button" class="calendar-btn calendar-move-today" data-action="move-today">Today</button>
        <button type="button" class="calendar-btn calendar-move-day" data-action="move-prev">
          <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
        </button>
        <button type="button" class="calendar-btn calendar-move-day" data-action="move-next">
          <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
        </button>
        </span>
        <span id="renderRange" class="render-range"></span>
        </div>
      <div id="calendar"></div>
    </div>
  </div>
</div>
<script>

    'use strict';

    var CalendarList = [];

    function CalendarInfo() {
        this.id = null;
        this.name = null;
        this.checked = true;
        this.color = null;
        this.bgColor = null;
        this.borderColor = null;
        this.dragBgColor = null;
    }

    function addCalendar(calendar) {
        CalendarList.push(calendar);
    }

    function findCalendar(id) {
        var found;

        CalendarList.forEach(function(calendar) {
            if (calendar.id === id) {
                found = calendar;
            }
        });

        return found || CalendarList[0];
    }

    function hexToRGBA(hex) {
        var radix = 16;
        var r = parseInt(hex.slice(1, 3), radix),
            g = parseInt(hex.slice(3, 5), radix),
            b = parseInt(hex.slice(5, 7), radix),
            a = parseInt(hex.slice(7, 9), radix) / 255 || 1;
        var rgba = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

        return rgba;
    }

    (function() {
        var calendar;
        var id = 0;

        calendar = new CalendarInfo();
        id += 1;
        calendar.id = String(id);
        calendar.name = '보라색';
        calendar.color = '#624AC0';//글자색
        calendar.bgColor = '#F0EFF6';//선택 아이콘, 배경색
        calendar.dragBgColor = '#f00';
        calendar.borderColor = '#624AC0';//시간아이콘, 좌측라인
        addCalendar(calendar);

        calendar = new CalendarInfo();
        id += 1;
        calendar.id = String(id);
        calendar.name = '주황색';
        calendar.color = '#FF8C1A';
        calendar.bgColor = '#FDF8F3';
        calendar.dragBgColor = '#f00';
        calendar.borderColor = '#FF8C1A';
        addCalendar(calendar);

        calendar = new CalendarInfo();
        id += 1;
        calendar.id = String(id);
        calendar.name = '초록색';
        calendar.color = '#578E1C';
        calendar.bgColor = '#EEF8F0';
        calendar.dragBgColor = '#f00';
        calendar.borderColor = '#578E1C';
        addCalendar(calendar);

        calendar = new CalendarInfo();
        id += 1;
        calendar.id = String(id);
        calendar.name = '검정색';
        calendar.color = '#404040';
        calendar.bgColor = '#f1f1f1';
        calendar.dragBgColor = '#f00';
        calendar.borderColor = '#404040';
        addCalendar(calendar);
    })();



    (function(window, Calendar) {


        var cal, resizeThrottled;
        var useCreationPopup = true;
        var useDetailPopup = true;
        var datePicker, selectedCalendar;

        cal = new Calendar('#calendar', {
            defaultView: 'month',
            useCreationPopup: useCreationPopup,
            useDetailPopup: useDetailPopup,
            calendars: CalendarList,
            template: {
                milestone: function(model) {
                    return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + model.bgColor + '">' + model.title + '</span>';
                },
                allday: function(schedule) {
                    return getTimeTemplate(schedule, true);
                },
                time: function(schedule) {
                    return getTimeTemplate(schedule, false);
                }
            },
            month : {
                daynames : ["일", "월", "화", "수", "목", "금", "토"]
            },
        });


        // event handlers
        cal.on({
            'clickMore': function(e) {
                console.log('clickMore', e);
            },
            'clickSchedule': function(e) {
                // console.log('clickSchedule', e);
            },
            'clickDayname': function(date) {
                console.log('clickDayname', date);
            },
            'beforeCreateSchedule': function(e) {
                // $("#create").fadeIn();

                saveNewSchedule(e);
            },
            'beforeUpdateSchedule': function(e) {
                var schedule = e.schedule;
                var changes = e.changes;

                console.log('beforeUpdateSchedule', e);

                if (changes != null){
                    var formData = new FormData();
                    formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
                    if (changes.calendarId != undefined){
                        formData.append("calendarId", changes.calendarId);
                        formData.append("color", e.calendar.color);
                        formData.append("bgColor", e.calendar.bgColor);
                        formData.append("dragBgColor", e.calendar.dragBgColor);
                        formData.append("borderColor", e.calendar.borderColor);
                    }
                    if (changes.title != undefined){
                        formData.append("title", changes.title);
                    }
                    if (changes.end != undefined){
                        formData.append("end", changes.end._date.valueOf());
                    }
                    if (changes.start != undefined){
                        formData.append("start", changes.start._date.valueOf());
                    }
                    if (changes.isAllDay != undefined){
                        formData.append("isAllDay", changes.isAllDay);
                    }

                    formData.append("id", e.schedule.id);

                    console.log("formData", formData);

                    $.ajax({
                        url: "/untact/update_schedule",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            $('#overlay').fadeIn();
                        },
                        complete: function () {
                            $('#overlay').fadeOut();
                        },
                        success: function (json) {
                            cal.updateSchedule(schedule.id, schedule.calendarId, changes);
                            refreshScheduleVisibility();
                        },
                        error: function (data, status, er) {
                            $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
                            $("#myModal").modal('show');
                        }
                    });
                }

            },
            'beforeDeleteSchedule': function(e) {
                console.log('beforeDeleteSchedule', e);

                var formData = new FormData();

                formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
    			formData.append("id", e.schedule.id);

                $.ajax({
                    url: "/untact/delete_schedule",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#overlay').fadeIn();
                    },
                    complete: function () {
                        $('#overlay').fadeOut();
                    },
                    success: function (json) {
                        cal.deleteSchedule(e.schedule.id, e.schedule.calendarId);
                    },
                    error: function (data, status, er) {
                        $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
                        $("#myModal").modal('show');
                    }
                });
            },
            'afterRenderSchedule': function(e) {
                var schedule = e.schedule;
                // var element = cal.getElement(schedule.id, schedule.calendarId);
                // console.log('afterRenderSchedule', element);
            },
            'clickTimezonesCollapseBtn': function(timezonesCollapsed) {
                console.log('timezonesCollapsed', timezonesCollapsed);

                if (timezonesCollapsed) {
                    cal.setTheme({
                        'week.daygridLeft.width': '77px',
                        'week.timegridLeft.width': '77px'
                    });
                } else {
                    cal.setTheme({
                        'week.daygridLeft.width': '60px',
                        'week.timegridLeft.width': '60px'
                    });
                }

                return true;
            }
        });

        function getTimeTemplate(schedule, isAllDay) {
            var html = [];
            var start = moment(schedule.start.toUTCString());
            if (!isAllDay) {
                html.push('<strong>' + start.format('HH:mm') + '</strong> ');
            }
            if (schedule.isPrivate) {
                html.push('<span class="calendar-font-icon ic-lock-b"></span>');
                html.push(' Private');
            } else {
                if (schedule.isReadOnly) {
                    html.push('<span class="calendar-font-icon ic-readonly-b"></span>');
                } else if (schedule.recurrenceRule) {
                    html.push('<span class="calendar-font-icon ic-repeat-b"></span>');
                } else if (schedule.attendees.length) {
                    html.push('<span class="calendar-font-icon ic-user-b"></span>');
                } else if (schedule.location) {
                    html.push('<span class="calendar-font-icon ic-location-b"></span>');
                }
                html.push(' ' + schedule.title);
            }

            return html.join('');
        }

        function onClickNavi(e) {
            var action = getDataAction(e.target);

            switch (action) {
                case 'move-prev':
                    cal.prev();
                    break;
                case 'move-next':
                    cal.next();
                    break;
                case 'move-today':
                    cal.today();
                    break;
                default:
                    return;
            }

            setRenderRangeText();
            setSchedules();
        }

        function onNewSchedule() {
            var title = $('#new-schedule-title').val();
            var location = $('#new-schedule-location').val();
            var isAllDay = document.getElementById('new-schedule-allday').checked;
            var start = datePicker.getStartDate();
            var end = datePicker.getEndDate();
            var calendar = selectedCalendar ? selectedCalendar : CalendarList[0];

            if (!title) {
                return;
            }

            console.log('calendar.id ', calendar.id)
            cal.createSchedules([{
                id: '1',
                calendarId: calendar.id,
                title: title,
                isAllDay: isAllDay,
                start: start,
                end: end,
                category: isAllDay ? 'allday' : 'time',
                dueDateClass: '',
                color: calendar.color,
                bgColor: calendar.bgColor,
                dragBgColor: calendar.bgColor,
                borderColor: calendar.borderColor,
                raw: {
                    location: location
                },
                state: 'Busy'
            }]);

            $('#modal-new-schedule').modal('hide');
        }

        function onChangeNewScheduleCalendar(e) {
            var target = $(e.target).closest('a[role="menuitem"]')[0];
            var calendarId = getDataAction(target);
            changeNewScheduleCalendar(calendarId);
        }

        function changeNewScheduleCalendar(calendarId) {
            var calendarNameElement = document.getElementById('calendarName');
            var calendar = findCalendar(calendarId);
            var html = [];

            html.push('<span class="calendar-bar" style="background-color: ' + calendar.bgColor + '; border-color:' + calendar.borderColor + ';"></span>');
            html.push('<span class="calendar-name">' + calendar.name + '</span>');

            calendarNameElement.innerHTML = html.join('');

            selectedCalendar = calendar;
        }

        function createNewSchedule(event) {
            console.log("as");
            var start = event.start ? new Date(event.start.getTime()) : new Date();
            var end = event.end ? new Date(event.end.getTime()) : moment().add(1, 'hours').toDate();

            if (useCreationPopup) {
                cal.openCreationPopup({
                    start: start,
                    end: end
                });
            }
        }

        // 저장하기
        function saveNewSchedule(scheduleData) {

            var calendar = scheduleData.calendar || findCalendar(scheduleData.calendarId);

            var formData = new FormData();
            formData.append("<?=$this->security->get_csrf_token_name()?>", "<?=$this->security->get_csrf_hash()?>");
            formData.append("calendarId", scheduleData.calendarId);
            formData.append("end", scheduleData.end._date.valueOf());
            formData.append("isAllDay", scheduleData.isAllDay);
            formData.append("isPrivate", scheduleData.isPrivate);
            formData.append("location", scheduleData.location);
            formData.append("start", scheduleData.start._date.valueOf());
            formData.append("state", scheduleData.state);
            formData.append("title", scheduleData.title);
            formData.append("useCreationPopup", scheduleData.useCreationPopup);
            formData.append("color", calendar.color);
            formData.append("bgColor", calendar.bgColor);
            formData.append("dragBgColor", calendar.dragBgColor);
            formData.append("borderColor", calendar.borderColor);

            console.log("scheduleData", scheduleData);
            console.log("calendar", calendar);

            $.ajax({
                url: "/untact/insert_schedule",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#overlay').fadeIn();
                },
                complete: function () {
                    $('#overlay').fadeOut();
                },
                success: function (json) {
                    var schedule = {
                        id: json.id,
                        title: scheduleData.title,
                        isAllDay: scheduleData.isAllDay,
                        start: scheduleData.start,
                        end: scheduleData.end,
                        category: 'allday',
                        category: scheduleData.isAllDay ? 'allday' : 'time',
                        dueDateClass: '',
                        color: calendar.color,
                        bgColor: calendar.bgColor,
                        dragBgColor: calendar.bgColor,
                        borderColor: calendar.borderColor,
                        location: scheduleData.location,
                        // raw: {
                        //     class: scheduleData.raw['class']
                        // },
                        state: scheduleData.state
                    };
                    if (calendar) {
                        schedule.calendarId = calendar.id;
                        schedule.color = calendar.color;
                        schedule.bgColor = calendar.bgColor;
                        schedule.borderColor = calendar.borderColor;
                    }

                    cal.createSchedules([schedule]);

                    refreshScheduleVisibility();
                },
                error: function (data, status, er) {
                    $(".content").html("처리중 오류가 발생했습니다. 관리자에게 문의하십시오..");
                    $("#myModal").modal('show');
                }
            });

        }

        // 화면 로드 시(3)
        function refreshScheduleVisibility() {
            console.log("3");
            var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));

            CalendarList.forEach(function(calendar) {
                cal.toggleSchedules(calendar.id, !calendar.checked, false);
            });

            cal.render(true);

            calendarElements.forEach(function(input) {
                var span = input.nextElementSibling;
                span.style.backgroundColor = input.checked ? span.style.borderColor : 'transparent';
            });
        }

        // 화면 로드 시(1)
        function setRenderRangeText() {
            console.log("1");
            var renderRange = document.getElementById('renderRange');
            var options = cal.getOptions();
            var viewName = cal.getViewName();
            var html = [];
            if (viewName === 'day') {
                html.push(moment(cal.getDate().getTime()).format('MMM YYYY DD'));
            } else if (viewName === 'month' &&
                (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
                html.push(moment(cal.getDate().getTime()).format('YYYY년 MM월'));
            } else {
                html.push(moment(cal.getDateRangeStart().getTime()).format('MMM YYYY DD'));
                html.push(' ~ ');
                html.push(moment(cal.getDateRangeEnd().getTime()).format(' MMM DD'));
            }
            renderRange.innerHTML = html.join('');
        }

        // 화면 로드 시(2)
        function setSchedules() {
            console.log("2");
            cal.clear();
            //스케줄 넣는곳
            var schedules = new Array();
            <?foreach($schedule_list as $a){?>
            // var schedules = [
            //     {id: 489273, title: 'Workout for 2020-04-05', isAllDay: false, start: '2021-12-03T11:30:00+09:00', end: '2021-12-03T12:00:00+09:00', goingDuration: 30, comingDuration: 30, color: '#ffffff', isVisible: true, bgColor: '#69BB2D', dragBgColor: '#69BB2D', borderColor: '#69BB2D', calendarId: '1', category: 'allday', dueDateClass: '', customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 489273, title: 'Workout for 2020-04-05', isAllDay: false, start: '2021-12-11T11:30:00+09:00', end: '2021-12-11T12:00:00+09:00', goingDuration: 30, comingDuration: 30, color: '#ffffff', isVisible: true, bgColor: '#69BB2D', dragBgColor: '#69BB2D', borderColor: '#69BB2D', calendarId: '2', category: 'allday', dueDateClass: '', customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'completed with blocks', isAllDay: false, start: '2021-12-20T09:00:00+09:00', end: '2021-12-20T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '1', category: 'allday', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'completed with blocks', isAllDay: false, start: '2021-12-25T09:00:00+09:00', end: '2021-12-25T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '1', category: 'allday', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'completed with blocks', isAllDay: false, start: '2021-12-28T09:00:00+09:00', end: '2021-12-28T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '1', category: 'allday', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'completed with blocks', isAllDay: false, start: '2021-12-07T09:00:00+09:00', end: '2021-12-07T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '1', category: 'allday', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'Time Schedule Need BGCOLOR', isAllDay: false, start: '2021-12-28T09:00:00+09:00', end: '2021-12-28T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '1', category: 'time', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''},
            //     {id: 18073, title: 'Time Schedule Need BGCOLOR', isAllDay: false, start: '2021-12-17T09:00:00+09:00', end: '2021-12-17T10:00:00+09:00', color: '#ffffff', isVisible: true, bgColor: '#54B8CC', dragBgColor: '#54B8CC', borderColor: '#54B8CC', calendarId: '3', category: 'time', dueDateClass: '', customStyle: '', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''}
            // ];
            schedules.push({id: Number("<?=$a->vs_id?>"), title: "<?=$a->vs_title?>", isAllDay: false, start: moment(Number("<?=$a->vs_start?>")).format("YYYY-MM-DDTHH:mm:ss+09:00"), end: moment(Number("<?=$a->vs_end?>")).format("YYYY-MM-DDTHH:mm:ss+09:00"), goingDuration: 30, comingDuration: 30, color: "<?=$a->vs_color?>", isVisible: true, bgColor: "<?=$a->vs_bgcolor?>", dragBgColor: "<?=$a->vs_drag_bgcolor?>", borderColor: "<?=$a->vs_border_color?>", calendarId: "<?=$a->vs_calendar_id?>", category: "<?=$a->vs_is_all_day == 0 ? 'time' : 'allday'?>", dueDateClass: '', customStyle: 'cursor: default;', isPending: false, isFocused: false, isReadOnly: false, isPrivate: false, location: '', attendees: '', recurrenceRule: '', state: ''});
            <?}?>
            // generateSchedule(cal.getViewName(), cal.getDateRangeStart(), cal.getDateRangeEnd());
            cal.createSchedules(schedules);
            // cal.createSchedules(schedules);
            refreshScheduleVisibility();
        }

        // 화면 로드 시(4)
        function setEventListener() {
            console.log("4");
            $('#menu-navi').on('click', onClickNavi);
            // $('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
            // $('#lnb-calendars').on('change', onChangeCalendars);

            $('#btn-save-schedule').on('click', onNewSchedule);
            $('#btn-new-schedule').on('click', createNewSchedule);

            $('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);

            window.addEventListener('resize', resizeThrottled);
        }

        function getDataAction(target) {
            return target.dataset ? target.dataset.action : target.getAttribute('data-action');
        }

        resizeThrottled = tui.util.throttle(function() {
            cal.render();
        }, 50);

        window.cal = cal;

        // setDropdownCalendarType();
        setRenderRangeText();
        setSchedules();
        setEventListener();
        }
    )(window, tui.Calendar);

    // set calendars
    (function() {
    // var calendarList = document.getElementById('calendarList');
    // var html = [];
    // CalendarList.forEach(function(calendar) {
    //     html.push('<div class="lnb-calendars-item"><label>' +
    //         '<input type="checkbox" class="tui-full-calendar-checkbox-round" value="' + calendar.id + '" checked>' +
    //         '<span style="border-color: ' + calendar.borderColor + '; background-color: ' + calendar.borderColor + ';"></span>' +
    //         '<span>' + calendar.name + '</span>' +
    //         '</label></div>'
    //     );
    // });
    // calendarList.innerHTML = html.join('\n');
    })();

</script>
